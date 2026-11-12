<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Converts the seed articles (database/seeds/articles/*.md) into Editor.js block JSON.
 * The format is a small Markdown subset plus ":::" directives for callouts, SVG, charts, FAQ and AI widgets.
 */
final class ArticleParser
{
    /** @return array{meta:array<string,string>,blocks:list<array<string,mixed>>} */
    public function parse(string $source): array
    {
        $source = str_replace(["\r\n", "\r"], "\n", $source);
        $meta = [];
        if (preg_match('/\A---\n(.*?)\n---\n/s', $source, $m)) {
            foreach (explode("\n", $m[1]) as $line) {
                if (str_contains($line, ':')) {
                    [$k, $v] = explode(':', $line, 2);
                    $meta[trim($k)] = trim($v);
                }
            }
            $source = substr($source, strlen($m[0]));
        }
        $lines = explode("\n", $source);
        $blocks = [];
        $n = count($lines);
        $i = 0;
        $add = static function (string $type, array $data) use (&$blocks): void {
            $blocks[] = ['id' => substr(md5($type . count($blocks) . json_encode($data)), 0, 10), 'type' => $type, 'data' => $data];
        };

        while ($i < $n) {
            $line = $lines[$i];
            if (trim($line) === '') {
                $i++;
                continue;
            }
            // Headings
            if (preg_match('/^(#{2,4})\s+(.+)$/', $line, $m)) {
                $add('header', ['text' => $this->inline($m[2]), 'level' => strlen($m[1])]);
                $i++;
                continue;
            }
            // Fenced code
            if (preg_match('/^```([a-z0-9+#-]*)\s*$/i', $line, $m)) {
                $code = [];
                $i++;
                while ($i < $n && !preg_match('/^```\s*$/', $lines[$i])) {
                    $code[] = $lines[$i++];
                }
                $i++;
                $add('code', ['language' => strtolower($m[1]) ?: 'plaintext', 'code' => implode("\n", $code)]);
                continue;
            }
            // Directives
            if (preg_match('/^:::([a-z]+)\s*(.*)$/', $line, $m)) {
                $body = [];
                $i++;
                while ($i < $n && trim($lines[$i]) !== ':::') {
                    $body[] = $lines[$i++];
                }
                $i++;
                $this->directive($m[1], trim($m[2]), $body, $add);
                continue;
            }
            // Tables
            if (str_starts_with($line, '|')) {
                $rows = [];
                $head = false;
                while ($i < $n && str_starts_with($lines[$i], '|')) {
                    $row = $lines[$i++];
                    if (preg_match('/^\|[\s:\-|]+\|\s*$/', $row)) {
                        $head = true;
                        continue;
                    }
                    $cells = preg_split('/(?<!\\\\)\|/', trim(trim($row), '|')) ?: [];
                    $rows[] = array_map(fn($c) => $this->inline(str_replace('\|', '|', trim($c))), $cells);
                }
                $add('table', ['withHeadings' => $head, 'content' => $rows]);
                continue;
            }
            // Lists: "- " unordered, "1. " ordered, "+ " checklist
            if (preg_match('/^(-|\+|\d+\.)\s+/', $line, $m)) {
                $style = $m[1] === '-' ? 'unordered' : ($m[1] === '+' ? 'checklist' : 'ordered');
                $re = $style === 'ordered' ? '/^\d+\.\s+(.*)$/' : '/^' . preg_quote($m[1], '/') . '\s+(.*)$/';
                $items = [];
                while ($i < $n && preg_match($re, $lines[$i], $mm)) {
                    $items[] = ['content' => $this->inline($mm[1]), 'meta' => $style === 'checklist' ? ['checked' => true] : new \stdClass(), 'items' => []];
                    $i++;
                }
                $add('list', ['style' => $style, 'meta' => new \stdClass(), 'items' => $items]);
                continue;
            }
            // Block quote ("> text", optional last line "> — source")
            if (str_starts_with($line, '> ')) {
                $text = [];
                $caption = '';
                while ($i < $n && str_starts_with($lines[$i], '> ')) {
                    $q = substr($lines[$i++], 2);
                    if (str_starts_with($q, '— ')) {
                        $caption = substr($q, strlen('— '));
                    } else {
                        $text[] = $q;
                    }
                }
                $add('quote', ['text' => $this->inline(implode(' ', $text)), 'caption' => $this->inline($caption), 'alignment' => 'left']);
                continue;
            }
            // Paragraph: consecutive plain lines
            $para = [];
            while ($i < $n && trim($lines[$i]) !== '' && !preg_match('/^(#{2,4}\s|```|:::|\||> |- |\+ |\d+\.\s)/', $lines[$i])) {
                $para[] = trim($lines[$i++]);
            }
            $add('paragraph', ['text' => $this->inline(implode(' ', $para))]);
        }
        return ['meta' => $meta, 'blocks' => $blocks];
    }

    /** @param list<string> $body */
    private function directive(string $name, string $arg, array $body, callable $add): void
    {
        $text = trim(implode("\n", $body));
        switch ($name) {
            case 'info':
            case 'tip':
            case 'warn':
            case 'danger':
                $add('callout', ['type' => $name, 'title' => $arg, 'text' => $this->inline(preg_replace('/\s*\n\s*/', ' ', $text) ?? '')]);
                break;
            case 'svg':
                $add('svg', ['code' => $text, 'caption' => $this->inline($arg)]);
                break;
            case 'chart':
            case 'ai':
                $data = json_decode($text, true);
                if (!is_array($data)) {
                    throw new \RuntimeException("Invalid JSON in :::$name block: " . json_last_error_msg() . ' near "' . mb_substr($text, 0, 60) . '"');
                }
                $add($name, $data);
                break;
            case 'faq':
                $items = [];
                foreach ($body as $l) {
                    if (str_starts_with($l, 'Q: ')) {
                        $items[] = ['q' => trim(substr($l, 3)), 'a' => ''];
                    } elseif (str_starts_with($l, 'A: ') && $items) {
                        $items[count($items) - 1]['a'] = $this->inline(trim(substr($l, 3)));
                    } elseif (trim($l) !== '' && $items) {
                        $items[count($items) - 1]['a'] .= ' ' . $this->inline(trim($l));
                    }
                }
                $add('faq', ['items' => $items]);
                break;
            default:
                throw new \RuntimeException('Unknown directive :::' . $name);
        }
    }

    /** Inline Markdown to the small HTML subset the editor understands: b, i, code, mark, a. */
    public function inline(string $s): string
    {
        $codes = [];
        $s = preg_replace_callback('/`([^`]+)`/', static function ($m) use (&$codes) {
            $codes[] = '<code>' . htmlspecialchars($m[1], ENT_NOQUOTES | ENT_HTML5, 'UTF-8') . '</code>';
            return "\x01" . (count($codes) - 1) . "\x02";
        }, $s) ?? $s;
        $s = htmlspecialchars($s, ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
        $s = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', static fn($m) => '<a href="' . htmlspecialchars(html_entity_decode($m[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8') . '">' . $m[1] . '</a>', $s) ?? $s;
        $s = preg_replace('/\*\*(.+?)\*\*/s', '<b>$1</b>', $s) ?? $s;
        $s = preg_replace('/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/s', '<i>$1</i>', $s) ?? $s;
        $s = preg_replace('/==(.+?)==/s', '<mark>$1</mark>', $s) ?? $s;
        return preg_replace_callback('/\x01(\d+)\x02/', static fn($m) => $codes[(int)$m[1]], $s) ?? $s;
    }
}
