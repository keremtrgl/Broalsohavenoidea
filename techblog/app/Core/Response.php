<?php
declare(strict_types=1);

namespace App\Core;

final class Response
{
    /** @param array<string,string> $headers */
    public function __construct(
        public string $body = '',
        public int $status = 200,
        public array $headers = ['Content-Type' => 'text/html; charset=UTF-8'],
        public bool $cacheable = false,
        public ?\Closure $stream = null,
    ) {
    }

    public static function html(string $body, int $status = 200): self
    {
        return new self($body, $status);
    }

    public static function json(mixed $data, int $status = 200): self
    {
        return new self(
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE) ?: '{}',
            $status,
            ['Content-Type' => 'application/json; charset=UTF-8', 'Cache-Control' => 'no-store']
        );
    }

    public static function redirect(string $to, int $status = 302): self
    {
        // Only local paths or the configured site origin are valid redirect targets.
        if (!str_starts_with($to, '/') || str_starts_with($to, '//')) {
            $to = str_starts_with($to, url('/')) ? $to : '/';
        }
        return new self('', $status, ['Location' => $to, 'Cache-Control' => 'no-store']);
    }

    public static function xml(string $body, string $type = 'application/xml'): self
    {
        return new self($body, 200, ['Content-Type' => $type . '; charset=UTF-8', 'Cache-Control' => 'public, max-age=900']);
    }

    public function header(string $k, string $v): self
    {
        $this->headers[$k] = $v;
        return $this;
    }

    /** Mark an HTML response as safe to store in the page cache. */
    public function cache(): self
    {
        $this->cacheable = true;
        return $this;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $k => $v) {
                header($k . ': ' . str_replace(["\r", "\n"], '', $v));
            }
        }
        if ($this->stream !== null) {
            ($this->stream)();
            return;
        }
        echo $this->body;
    }
}
