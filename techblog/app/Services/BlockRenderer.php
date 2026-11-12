<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Media;

/**
 * Turns Editor.js block JSON into safe HTML.
 * Every text value is escaped or purified, SVG is sanitized, and charts are emitted as data for Chart.js.
 */
final class BlockRenderer
{
    /** @var list<array{id:string,text:string,level:int}> */
    private array $toc = [];
    /** @var list<array{q:string,a:string}> */
    private array $faq = [];
    /** @var array<string,int> */
    private array $ids = [];
    private bool $hasChart = false;
    private bool $hasCode = false;
    private bool $hasAi = false;
    private bool $hasAnim = false;
    private string $text = '';

    public const CALLOUTS = ['info' => 'Bilgi', 'tip' => 'İpucu', 'warn' => 'Dikkat', 'danger' => 'Uyarı'];
    public const CHART_TYPES = ['bar', 'line', 'radar', 'scatter', 'doughnut'];
    public const AI_WIDGETS = ['models-table', 'benchmark-table', 'benchmark-chart', 'radar', 'scatter', 'picker', 'decision-table'];

    /**
     * @param list<array<string,mixed>> $blocks
     * @return array{html:string,toc:list<array{id:string,text:string,level:int}>,faq:list<array{q:string,a:string}>,has_chart:bool,has_code:bool,has_ai:bool,has_anim:bool,text:string,words:int}
     */
    public function render(array $blocks): array
    {
        $html = '';
        foreach ($blocks as $b) {
            if (!is_array($b) || !isset($b['type']) || !is_array($b['data'] ?? null)) {
                continue;
            }
            $d = $b['data'];
            $html .= match ($b['type']) {
                'header' => $this->header($d),
                'paragraph' => $this->paragraph($d),
                'list' => $this->listBlock($d),
                'code' => $this->code($d),
                'table' => $this->table($d),
                'quote' => $this->quote($d),
                'callout' => $this->callout($d),
                'image' => $this->image($d),
                'gallery' => $this->gallery($d),
                'svg' => $this->svg($d),
                'chart' => $this->chart($d),
                'faq' => $this->faqBlock($d),
                'ai' => $this->ai($d),
                default => '',
            } . "\n";
        }
        $text = trim(preg_replace('/\s+/u', ' ', $this->text) ?? '');
        return [
            'html' => $html, 'toc' => $this->toc, 'faq' => $this->faq,
            'has_chart' => $this->hasChart, 'has_code' => $this->hasCode, 'has_ai' => $this->hasAi, 'has_anim' => $this->hasAnim,
            'text' => $text, 'words' => self::countWords($text),
        ];
    }

    public static function countWords(string $text): int
    {
        return count(preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }

    private function say(string $html): void
    {
        $this->text .= ' ' . html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function uniqueId(string $text): string
    {
        $base = slugify($text) ?: 'bolum';
        $n = $this->ids[$base] = ($this->ids[$base] ?? 0) + 1;
        return $n === 1 ? $base : $base . '-' . $n;
    }

    private function header(array $d): string
    {
        $level = min(4, max(2, (int)($d['level'] ?? 2)));
        $inner = Sanitizer::inline((string)($d['text'] ?? ''));
        $plain = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($plain === '') {
            return '';
        }
        $id = $this->uniqueId($plain);
        if ($level <= 3) {
            $this->toc[] = ['id' => $id, 'text' => $plain, 'level' => $level];
        }
        $this->say($inner);
        return sprintf('<h%d id="%s">%s<a class="anchor" href="#%s" aria-label="Bu bölüme bağlantı: %s">#</a></h%d>', $level, e($id), $inner, e($id), e($plain), $level);
    }

    private function paragraph(array $d): string
    {
        $inner = Sanitizer::inline((string)($d['text'] ?? ''));
        if (trim(strip_tags($inner)) === '') {
            return '';
        }
        $this->say($inner);
        return '<p>' . $inner . '</p>';
    }

    private function listBlock(array $d): string
    {
        $style = (string)($d['style'] ?? 'unordered');
        $tag = $style === 'ordered' ? 'ol' : 'ul';
        $class = $style === 'checklist' ? ' class="checklist"' : '';
        $items = '';
        foreach ((array)($d['items'] ?? []) as $it) {
            // Editor.js list v1 uses plain strings, v2 uses {content, meta, items}.
            $content = is_array($it) ? (string)($it['content'] ?? '') : (string)$it;
            $inner = Sanitizer::inline($content);
            $this->say($inner);
            $nested = is_array($it) && !empty($it['items']) ? $this->listBlock(['style' => $style, 'items' => $it['items']]) : '';
            $items .= '<li>' . $inner . $nested . '</li>';
        }
        return $items === '' ? '' : "<$tag$class>$items</$tag>";
    }

    private function code(array $d): string
    {
        $code = (string)($d['code'] ?? '');
        if (trim($code) === '') {
            return '';
        }
        $lang = preg_replace('/[^a-z0-9+#-]/', '', strtolower((string)($d['language'] ?? ''))) ?: 'plaintext';
        $this->hasCode = true;
        $label = $lang === 'plaintext' ? 'Kod' : strtoupper($lang);
        return '<div class="code"><div class="code-bar"><span>' . e($label) . '</span><button type="button" class="copy" data-copy>Kopyala</button></div>'
            . '<pre tabindex="0"><code class="language-' . e($lang) . '">' . e($code) . '</code></pre></div>';
    }

    private function table(array $d): string
    {
        $rows = array_values(array_filter((array)($d['content'] ?? []), 'is_array'));
        if (!$rows) {
            return '';
        }
        $head = !empty($d['withHeadings']);
        $caption = Sanitizer::text((string)($d['caption'] ?? ''), 200);
        $out = '<div class="table-wrap" role="region" tabindex="0" aria-label="' . e($caption ?: 'Karşılaştırma tablosu') . '"><table>';
        if ($caption !== '') {
            $out .= '<caption>' . e($caption) . '</caption>';
        }
        foreach ($rows as $i => $row) {
            if ($head && $i === 0) {
                $out .= '<thead><tr>';
                foreach ($row as $c) {
                    $inner = Sanitizer::inline((string)$c);
                    $this->say($inner);
                    $out .= '<th scope="col">' . $inner . '</th>';
                }
                $out .= '</tr></thead><tbody>';
                continue;
            }
            if (!$head && $i === 0) {
                $out .= '<tbody>';
            }
            $out .= '<tr>';
            foreach (array_values($row) as $j => $c) {
                $inner = Sanitizer::inline((string)$c);
                $this->say($inner);
                $out .= $j === 0 && $head ? '<th scope="row">' . $inner . '</th>' : '<td>' . $inner . '</td>';
            }
            $out .= '</tr>';
        }
        return $out . '</tbody></table></div>';
    }

    private function quote(array $d): string
    {
        $inner = Sanitizer::inline((string)($d['text'] ?? ''));
        if (trim(strip_tags($inner)) === '') {
            return '';
        }
        $this->say($inner);
        $cap = Sanitizer::inline((string)($d['caption'] ?? ''));
        return '<blockquote><p>' . $inner . '</p>' . ($cap !== '' ? '<footer>— ' . $cap . '</footer>' : '') . '</blockquote>';
    }

    private function callout(array $d): string
    {
        $type = isset(self::CALLOUTS[$d['type'] ?? '']) ? (string)$d['type'] : 'info';
        $title = Sanitizer::text((string)($d['title'] ?? ''), 150) ?: self::CALLOUTS[$type];
        $inner = Sanitizer::inline((string)($d['text'] ?? ''));
        $this->say($title . ' ' . $inner);
        return '<aside class="callout callout-' . $type . '" role="note"><p class="callout-title">' . e($title) . '</p><p>' . $inner . '</p></aside>';
    }

    /** @return array{src:string,alt:string,w:int,h:int,sources:string} */
    private function picture(array $d): array
    {
        $src = Sanitizer::localPath((string)($d['url'] ?? ''));
        $alt = Sanitizer::text((string)($d['alt'] ?? ''), 300);
        $w = max(0, (int)($d['width'] ?? 0));
        $h = max(0, (int)($d['height'] ?? 0));
        $sources = '';
        $mediaId = (int)($d['media_id'] ?? 0);
        if ($mediaId > 0 && ($m = Media::find($mediaId))) {
            $src = $m['path'];
            $w = (int)$m['width'];
            $h = (int)$m['height'];
            $alt = $alt ?: (string)$m['alt'];
            $sources = Media::sourcesHtml($m, '(min-width: 800px) 760px, 100vw');
        }
        return ['src' => $src, 'alt' => $alt, 'w' => $w, 'h' => $h, 'sources' => $sources];
    }

    private function imgTag(array $p): string
    {
        $dims = $p['w'] > 0 && $p['h'] > 0 ? ' width="' . $p['w'] . '" height="' . $p['h'] . '"' : '';
        return '<picture>' . $p['sources'] . '<img src="' . e($p['src']) . '" alt="' . e($p['alt']) . '"' . $dims . ' loading="lazy" decoding="async"></picture>';
    }

    private function image(array $d): string
    {
        $p = $this->picture($d);
        if ($p['src'] === '') {
            return '';
        }
        $cap = Sanitizer::inline((string)($d['caption'] ?? ''));
        $this->say($p['alt'] . ' ' . $cap);
        return '<figure class="fig">' . $this->imgTag($p) . ($cap !== '' ? '<figcaption>' . $cap . '</figcaption>' : '') . '</figure>';
    }

    private function gallery(array $d): string
    {
        $out = '';
        foreach ((array)($d['items'] ?? []) as $it) {
            if (!is_array($it)) {
                continue;
            }
            $p = $this->picture($it);
            if ($p['src'] !== '') {
                $this->say($p['alt']);
                $out .= '<li>' . $this->imgTag($p) . '</li>';
            }
        }
        if ($out === '') {
            return '';
        }
        $cap = Sanitizer::inline((string)($d['caption'] ?? ''));
        return '<figure class="gallery"><ul>' . $out . '</ul>' . ($cap !== '' ? '<figcaption>' . $cap . '</figcaption>' : '') . '</figure>';
    }

    private function svg(array $d): string
    {
        $svg = Sanitizer::svg((string)($d['code'] ?? ''));
        if ($svg === '') {
            return '';
        }
        $cap = Sanitizer::inline((string)($d['caption'] ?? ''));
        $this->say($cap);
        if (str_contains($svg, 'data-a=')) {
            $this->hasAnim = true;
        }
        return '<figure class="fig fx">' . $svg . ($cap !== '' ? '<figcaption>' . $cap . '</figcaption>' : '') . '</figure>';
    }

    private function chart(array $d): string
    {
        $type = in_array($d['type'] ?? '', self::CHART_TYPES, true) ? (string)$d['type'] : 'bar';
        $labels = array_map(static fn($l) => Sanitizer::text((string)$l, 80), array_values((array)($d['labels'] ?? [])));
        $datasets = [];
        foreach ((array)($d['datasets'] ?? []) as $ds) {
            if (!is_array($ds)) {
                continue;
            }
            $data = [];
            foreach (array_values((array)($ds['data'] ?? [])) as $v) {
                if (is_array($v)) {
                    $data[] = ['x' => (float)($v['x'] ?? 0), 'y' => (float)($v['y'] ?? 0), 'label' => Sanitizer::text((string)($v['label'] ?? ''), 80)];
                } else {
                    $data[] = is_numeric($v) ? (float)$v : null;
                }
            }
            $datasets[] = ['label' => Sanitizer::text((string)($ds['label'] ?? ''), 80), 'data' => $data];
        }
        if (!$datasets) {
            return '';
        }
        $title = Sanitizer::text((string)($d['title'] ?? ''), 200);
        $cfg = [
            'type' => $type, 'labels' => $labels, 'datasets' => $datasets,
            'horizontal' => !empty($d['horizontal']), 'stacked' => !empty($d['stacked']), 'log' => !empty($d['log']),
            'unit' => Sanitizer::text((string)($d['unit'] ?? ''), 20),
            'xTitle' => Sanitizer::text((string)($d['xTitle'] ?? ''), 80),
            'yTitle' => Sanitizer::text((string)($d['yTitle'] ?? ''), 80),
        ];
        $this->hasChart = true;
        $this->say($title);

        // Accessible data table: the same numbers for screen readers and no-JS visitors.
        $tbl = '<table><thead><tr><th scope="col">' . e($type === 'scatter' ? 'Öğe' : 'Kategori') . '</th>';
        if ($type === 'scatter') {
            $tbl .= '<th scope="col">' . e($cfg['xTitle'] ?: 'X') . '</th><th scope="col">' . e($cfg['yTitle'] ?: 'Y') . '</th></tr></thead><tbody>';
            foreach ($datasets as $ds) {
                foreach ($ds['data'] as $pt) {
                    if (is_array($pt)) {
                        $tbl .= '<tr><th scope="row">' . e($pt['label'] ?: $ds['label']) . '</th><td>' . e(self::fmt($pt['x'])) . '</td><td>' . e(self::fmt($pt['y'])) . '</td></tr>';
                    }
                }
            }
        } else {
            foreach ($datasets as $ds) {
                $tbl .= '<th scope="col">' . e($ds['label'] ?: 'Değer') . '</th>';
            }
            $tbl .= '</tr></thead><tbody>';
            foreach ($labels as $i => $l) {
                $tbl .= '<tr><th scope="row">' . e($l) . '</th>';
                foreach ($datasets as $ds) {
                    $v = $ds['data'][$i] ?? null;
                    $tbl .= '<td>' . ($v === null || is_array($v) ? '—' : e(self::fmt($v) . ($cfg['unit'] !== '' ? ' ' . $cfg['unit'] : ''))) . '</td>';
                }
                $tbl .= '</tr>';
            }
        }
        $tbl .= '</tbody></table>';

        return '<figure class="chart" data-chart="' . e(json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '">'
            . ($title !== '' ? '<p class="chart-title">' . e($title) . '</p>' : '')
            . '<div class="chart-box"><canvas role="img" aria-label="' . e(($title ?: 'Grafik') . '. Veriler aşağıdaki tabloda.') . '"></canvas></div>'
            . '<details class="chart-data"><summary>Veri tablosunu göster</summary><div class="table-wrap" role="region" tabindex="0" aria-label="' . e($title ?: 'Grafik verisi') . '">' . $tbl . '</div></details>'
            . $this->sourceLine($d)
            . '</figure>';
    }

    private function sourceLine(array $d): string
    {
        $src = Sanitizer::text((string)($d['source'] ?? ''), 200);
        $url = Sanitizer::url((string)($d['sourceUrl'] ?? ''));
        $date = Sanitizer::text((string)($d['date'] ?? ''), 40);
        $note = Sanitizer::text((string)($d['note'] ?? ''), 300);
        if ($src === '' && $date === '' && $note === '') {
            return '';
        }
        $out = '<figcaption class="src">';
        if ($src !== '') {
            $out .= 'Kaynak: ' . ($url !== '' ? '<a href="' . e($url) . '" target="_blank" rel="noopener noreferrer">' . e($src) . '</a>' : e($src));
        }
        if ($date !== '') {
            $out .= ($src !== '' ? ' · ' : '') . 'Veri tarihi: ' . e($date);
        }
        if ($note !== '') {
            $out .= ' · ' . e($note);
        }
        return $out . '</figcaption>';
    }

    private function faqBlock(array $d): string
    {
        $out = '';
        foreach ((array)($d['items'] ?? []) as $it) {
            if (!is_array($it)) {
                continue;
            }
            $q = Sanitizer::text((string)($it['q'] ?? ''), 300);
            $a = Sanitizer::inline((string)($it['a'] ?? ''));
            if ($q === '' || trim(strip_tags($a)) === '') {
                continue;
            }
            $this->faq[] = ['q' => $q, 'a' => trim(html_entity_decode(strip_tags($a), ENT_QUOTES | ENT_HTML5, 'UTF-8'))];
            $this->say($q . ' ' . $a);
            $out .= '<details class="faq-item"><summary>' . e($q) . '</summary><p>' . $a . '</p></details>';
        }
        return $out === '' ? '' : '<div class="faq">' . $out . '</div>';
    }

    // ---- AI data widgets (tables are rendered on the server from data/*.json) ----

    private function ai(array $d): string
    {
        $widget = (string)($d['widget'] ?? '');
        if (!in_array($widget, self::AI_WIDGETS, true)) {
            return '';
        }
        $this->hasAi = true;
        $bench = preg_replace('/[^a-z0-9_]/', '', (string)($d['benchmark'] ?? '')) ?? '';
        $type = isset(AiData::TYPES[$d['model_type'] ?? '']) ? (string)$d['model_type'] : 'llm';
        return match ($widget) {
            'models-table' => $this->aiModelsTable($type, array_values(array_filter(array_map('strval', (array)($d['benchmarks'] ?? []))))),
            'benchmark-table' => $this->aiBenchmarkTable($bench),
            'benchmark-chart' => $this->aiChart('bar', $bench),
            'radar' => $this->aiChart('radar', ''),
            'scatter' => $this->aiChart('scatter', $bench),
            'picker' => $this->aiPicker(),
            'decision-table' => $this->aiDecisionTable(),
        };
    }

    public static function fmt(float|int $v): string
    {
        $dec = fmod((float)$v, 1.0) === 0.0 ? 0 : (abs($v) < 10 ? 2 : 1);
        return rtrim(rtrim(number_format((float)$v, $dec, ',', '.'), '0'), ',') ?: '0';
    }

    private static function fmtContext(mixed $tokens): string
    {
        if (!is_numeric($tokens) || $tokens <= 0) {
            return '—';
        }
        return $tokens >= 1000000 ? self::fmt($tokens / 1000000) . ' M' : self::fmt($tokens / 1000) . ' K';
    }

    /** Score cell: value, source link, date and a "vendor-reported" label when applicable. */
    private function scoreCell(?array $s, ?array $bm): string
    {
        if (!$s || !is_numeric($s['value'] ?? null)) {
            return '<span class="na" aria-label="Veri yok">—</span>';
        }
        $unit = (string)($bm['unit'] ?? '');
        $val = self::fmt((float)$s['value']) . ($unit !== '' ? ($unit === '%' ? ' %' : ' ' . $unit) : '');
        $url = Sanitizer::url((string)($s['source_url'] ?? ''));
        $name = (string)($s['source_name'] ?? 'Kaynak');
        $date = (string)($s['date'] ?? '');
        $out = '<span class="score">' . e($val) . '</span>';
        $note = Sanitizer::text((string)($s['note'] ?? ''), 80);
        if ($note !== '') {
            $out .= ' <small class="score-note">(' . e($note) . ')</small>';
        }
        if (($s['reported_by'] ?? '') === 'vendor') {
            $out .= ' <span class="tagx tagx-vendor">üretici beyanı</span>';
        }
        $out .= '<small class="score-src">' . ($url !== '' ? '<a href="' . e($url) . '" target="_blank" rel="noopener noreferrer">' . e($name) . '</a>' : e($name))
            . ($date !== '' ? ' · ' . e($date) : '') . '</small>';
        return $out;
    }

    private const MODALITY = ['text' => 'metin', 'image' => 'görsel', 'audio' => 'ses', 'video' => 'video', 'pdf' => 'PDF'];

    /** @param list<string> $benchIds */
    private function aiModelsTable(string $type, array $benchIds): string
    {
        $data = AiData::publicData();
        $models = array_values(array_filter($data['models'], static fn($m) => ($m['type'] ?? 'llm') === $type));
        if (!$models) {
            return '<p class="na-block">Bu tablo için yayımlanmış veri bulunmuyor.</p>';
        }
        $benches = array_values(array_filter(array_map([AiData::class, 'benchmark'], $benchIds)));
        $llm = $type === 'llm';
        $h = '<th scope="col">Model</th><th scope="col">Sağlayıcı</th><th scope="col">Açık / kapalı</th>';
        if ($llm) {
            $h .= '<th scope="col">Bağlam penceresi</th><th scope="col">Girdi türleri</th><th scope="col">API fiyatı (1M girdi / çıktı)</th>';
        }
        foreach ($benches as $b) {
            $h .= '<th scope="col">' . e((string)$b['name']) . '</th>';
        }
        $h .= '<th scope="col">En uygun kullanım</th>';
        $rows = '';
        foreach ($models as $m) {
            $r = '<th scope="row">' . e((string)$m['name']) . (!empty($m['release']) ? '<small class="score-src">Çıkış: ' . e((string)$m['release']) . '</small>' : '') . '</th>';
            $r .= '<td>' . e((string)($m['provider'] ?? '')) . '</td>';
            $r .= '<td>' . (!empty($m['open']) ? '<span class="tagx tagx-open">Açık ağırlık</span>' : '<span class="tagx">Kapalı</span>')
                . (!empty($m['license']) ? '<small class="score-src">' . e((string)$m['license']) . '</small>' : '') . '</td>';
            if ($llm) {
                $r .= '<td>' . e(self::fmtContext($m['context_window'] ?? null)) . '</td>';
                $mods = array_map(static fn($x) => self::MODALITY[$x] ?? $x, (array)($m['modalities'] ?? []));
                $r .= '<td>' . ($mods ? e(implode(', ', $mods)) : '—') . '</td>';
                if (is_numeric($m['price_in'] ?? null) && is_numeric($m['price_out'] ?? null)) {
                    $purl = Sanitizer::url((string)($m['price_source_url'] ?? ''));
                    $r .= '<td><span class="score">$' . e(self::fmt((float)$m['price_in'])) . ' / $' . e(self::fmt((float)$m['price_out'])) . '</span>'
                        . '<small class="score-src">' . ($purl !== '' ? '<a href="' . e($purl) . '" target="_blank" rel="noopener noreferrer">fiyat sayfası</a>' : 'fiyat')
                        . (!empty($m['price_date']) ? ' · ' . e((string)$m['price_date']) : '') . '</small></td>';
                } else {
                    $r .= '<td><span class="na" aria-label="Veri yok">—</span>' . (!empty($m['open']) ? '<small class="score-src">kendi donanımınızda çalışır</small>' : '') . '</td>';
                }
            }
            foreach ($benches as $b) {
                $r .= '<td>' . $this->scoreCell(AiData::score((string)$m['id'], (string)$b['id']), $b) . '</td>';
            }
            $r .= '<td>' . e((string)($m['best_for'] ?? '—')) . '</td>';
            $rows .= '<tr>' . $r . '</tr>';
            $this->say((string)$m['name'] . ' ' . ($m['provider'] ?? '') . ' ' . ($m['best_for'] ?? ''));
        }
        return '<div class="ai-block"><div class="table-wrap" role="region" tabindex="0" aria-label="' . e(AiData::TYPES[$type]) . ' karşılaştırma tablosu"><table class="ai-table"><thead><tr>'
            . $h . '</tr></thead><tbody>' . $rows . '</tbody></table></div>' . $this->aiFoot() . '</div>';
    }

    private function aiFoot(): string
    {
        return '<p class="src">Veriler <code>data/ai-models.json</code> ve <code>data/ai-benchmarks.json</code> dosyalarından okunur · Son veri güncellemesi: '
            . e(tr_date(AiData::updated())) . ' · “—” doğrulanmış veri bulunmadığını gösterir.</p>';
    }

    private function aiBenchmarkTable(string $benchId): string
    {
        $bm = AiData::benchmark($benchId);
        $rank = $bm ? AiData::ranking($benchId) : [];
        if (!$bm || !$rank) {
            return '<p class="na-block">Bu kıyaslama için doğrulanmış skor bulunmuyor (—).</p>';
        }
        $rows = '';
        foreach ($rank as $i => $r) {
            $rows .= '<tr><td>' . ($i + 1) . '</td><th scope="row">' . e((string)$r['model']['name']) . '</th><td>' . e((string)($r['model']['provider'] ?? '')) . '</td><td>'
                . $this->scoreCell($r['score'], $bm) . '</td></tr>';
        }
        return '<div class="ai-block"><div class="table-wrap" role="region" tabindex="0" aria-label="' . e((string)$bm['name']) . ' sıralaması"><table class="ai-table"><caption>' . e((string)$bm['name'])
            . '</caption><thead><tr><th scope="col">#</th><th scope="col">Model</th><th scope="col">Sağlayıcı</th><th scope="col">Skor ve kaynak</th></tr></thead><tbody>'
            . $rows . '</tbody></table></div>' . $this->aiFoot() . '</div>';
    }

    private function aiChart(string $kind, string $benchId): string
    {
        $bm = $benchId !== '' ? AiData::benchmark($benchId) : null;
        if ($kind !== 'radar' && !$bm) {
            return '';
        }
        $this->hasChart = true;
        $title = match ($kind) {
            'bar' => (string)$bm['name'] . ' skorları',
            'scatter' => 'Fiyat / performans: API maliyeti ve ' . (string)$bm['name'],
            default => 'Modellerin güçlü yönleri (0–100 arası normalize edilmiş)',
        };
        $fallback = $kind === 'radar' ? '' : '<details class="chart-data"><summary>Veri tablosunu göster</summary>' . $this->aiBenchmarkTable($benchId) . '</details>';
        return '<figure class="chart" data-ai-chart="' . e($kind) . '" data-benchmark="' . e($benchId) . '">'
            . '<p class="chart-title">' . e($title) . '</p>'
            . '<div class="chart-box' . ($kind === 'radar' ? ' chart-box-tall' : '') . '"><canvas role="img" aria-label="' . e($title) . '"></canvas></div>'
            . '<div class="chart-legend" data-ai-note></div>' . $fallback
            . '<figcaption class="src">Grafik, sayfa açıldığında <code>data/</code> altındaki JSON verisinden çizilir · Son veri güncellemesi: ' . e(tr_date(AiData::updated())) . '</figcaption>'
            . '</figure>';
    }

    private function aiPicker(): string
    {
        $cases = AiData::models()['use_cases'];
        $opts = '';
        foreach ($cases as $c) {
            $opts .= '<option value="' . e((string)$c['id']) . '">' . e((string)$c['label']) . '</option>';
        }
        return '<section class="picker" data-ai-picker aria-labelledby="picker-title">'
            . '<h3 id="picker-title" class="picker-title">Hangi yapay zekâ bana uygun?</h3>'
            . '<p class="picker-lead">Amacınızı, bütçenizi ve gizlilik ihtiyacınızı seçin; öneri, bu sayfadaki tabloların kullandığı aynı veriden tarayıcınızda hesaplanır. Hiçbir seçiminiz sunucuya gönderilmez.</p>'
            . '<form class="picker-form">'
            . '<label>Amacım<select name="goal">' . $opts . '</select></label>'
            . '<label>Bütçem<select name="budget"><option value="any">Fark etmez</option><option value="low">Düşük maliyetli API</option><option value="free">Ücretsiz</option></select></label>'
            . '<label>Gizlilik<select name="privacy"><option value="cloud">Bulut hizmeti kullanabilirim</option><option value="local">Veri bende kalmalı (kendi sunucum / bilgisayarım)</option></select></label>'
            . '<button type="submit" class="btn">Öneriyi göster</button></form>'
            . '<div class="picker-result" aria-live="polite"><p class="na-block">Seçimlerinizi yapıp “Öneriyi göster” düğmesine basın.</p></div>'
            . '<noscript><p class="na-block">Bu araç JavaScript gerektirir. Aynı bilgiler aşağıdaki karar tablosunda yer alır.</p></noscript>'
            . '</section>';
    }

    private function aiDecisionTable(): string
    {
        $rows = '';
        foreach (AiData::models()['use_cases'] as $c) {
            $bm = AiData::benchmark((string)($c['benchmark'] ?? ''));
            $type = (string)($c['model_type'] ?? 'llm');
            $rank = $bm ? AiData::ranking((string)$bm['id'], static fn($m) => ($m['type'] ?? 'llm') === $type) : [];
            $open = $bm ? AiData::ranking((string)$bm['id'], static fn($m) => ($m['type'] ?? 'llm') === $type && !empty($m['open'])) : [];
            $best = $rank[0] ?? null;
            $rows .= '<tr><th scope="row">' . e((string)$c['label']) . '</th>'
                . '<td>' . ($best ? '<strong>' . e((string)$best['model']['name']) . '</strong><small class="score-src">' . e((string)($best['model']['provider'] ?? '')) . '</small>' : '<span class="na">—</span>') . '</td>'
                . '<td>' . ($bm ? e((string)$bm['name']) . '<br>' . $this->scoreCell($best['score'] ?? null, $bm) : '<span class="na">—</span>') . '</td>'
                . '<td>' . (isset($rank[1]) ? e((string)$rank[1]['model']['name']) : '<span class="na">—</span>') . '</td>'
                . '<td>' . (isset($open[0]) ? e((string)$open[0]['model']['name']) : '<span class="na">—</span>') . '</td></tr>';
            $this->say((string)$c['label'] . ' ' . ($best['model']['name'] ?? ''));
        }
        if ($rows === '') {
            return '<p class="na-block">Karar tablosu için veri bulunmuyor.</p>';
        }
        return '<div class="ai-block"><div class="table-wrap" role="region" tabindex="0" aria-label="Amaca göre karar tablosu"><table class="ai-table"><thead><tr>'
            . '<th scope="col">Amaç</th><th scope="col">Veriye göre ilk sıra</th><th scope="col">Ölçüt ve skor</th><th scope="col">İkinci sıra</th><th scope="col">En iyi açık ağırlıklı seçenek</th>'
            . '</tr></thead><tbody>' . $rows . '</tbody></table></div>' . $this->aiFoot() . '</div>';
    }
}
