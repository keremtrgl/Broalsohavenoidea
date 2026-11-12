<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use App\Core\PageCache;

/**
 * AI model and benchmark data, stored as JSON files under data/.
 * Tables, charts and the "Which AI is right for me?" tool all read from these files.
 */
final class AiData
{
    public const TYPES = ['llm' => 'Dil modeli (LLM)', 'image' => 'Görsel üretimi', 'video' => 'Video üretimi', 'audio' => 'Ses / konuşma / müzik', 'embedding' => 'Embedding'];
    public const CATEGORIES = [
        'knowledge' => 'Genel bilgi ve akıl yürütme', 'math' => 'Matematik', 'coding' => 'Kodlama',
        'agents' => 'Ajanlar ve araç kullanımı', 'multimodal' => 'Çok kipli anlama', 'context' => 'Uzun bağlam',
        'preference' => 'İnsan tercihi', 'generation' => 'Görsel / video / ses üretimi', 'speed' => 'Hız ve maliyet',
    ];

    private static ?array $models = null;
    private static ?array $bench = null;

    private static function path(string $name): string
    {
        return base_path('data/' . $name . '.json');
    }

    /** @return array<string,mixed> */
    private static function read(string $name): array
    {
        $f = self::path($name);
        $d = is_file($f) ? json_decode((string)file_get_contents($f), true) : null;
        return is_array($d) ? $d : [];
    }

    /** @return array{updated:string,models:list<array<string,mixed>>,use_cases:list<array<string,mixed>>} */
    public static function models(): array
    {
        if (self::$models === null) {
            $d = self::read('ai-models');
            self::$models = ['updated' => (string)($d['updated'] ?? ''), 'models' => array_values($d['models'] ?? []), 'use_cases' => array_values($d['use_cases'] ?? []), 'radar' => (array)($d['radar'] ?? [])];
        }
        return self::$models;
    }

    /** @return array{updated:string,benchmarks:list<array<string,mixed>>,scores:list<array<string,mixed>>} */
    public static function benchmarks(): array
    {
        if (self::$bench === null) {
            $d = self::read('ai-benchmarks');
            self::$bench = ['updated' => (string)($d['updated'] ?? ''), 'benchmarks' => array_values($d['benchmarks'] ?? []), 'scores' => array_values($d['scores'] ?? [])];
        }
        return self::$bench;
    }

    /** Most recent "updated" date of both files (Y-m-d). */
    public static function updated(): string
    {
        return max(self::models()['updated'], self::benchmarks()['updated']);
    }

    /** Data set exposed to the browser: published models and their scores only. */
    public static function publicData(): array
    {
        $m = self::models();
        $b = self::benchmarks();
        $models = array_values(array_filter($m['models'], static fn($x) => !empty($x['published'])));
        $ids = array_column($models, 'id');
        $scores = array_values(array_filter($b['scores'], static fn($s) => in_array($s['model'] ?? '', $ids, true)));
        return ['updated' => self::updated(), 'models' => $models, 'use_cases' => $m['use_cases'], 'radar' => $m['radar'], 'benchmarks' => $b['benchmarks'], 'scores' => $scores];
    }

    /** @return array<string,mixed>|null */
    public static function score(string $modelId, string $benchmarkId): ?array
    {
        foreach (self::benchmarks()['scores'] as $s) {
            if (($s['model'] ?? '') === $modelId && ($s['benchmark'] ?? '') === $benchmarkId) {
                return $s;
            }
        }
        return null;
    }

    /** @return array<string,mixed>|null */
    public static function benchmark(string $id): ?array
    {
        foreach (self::benchmarks()['benchmarks'] as $b) {
            if (($b['id'] ?? '') === $id) {
                return $b;
            }
        }
        return null;
    }

    /**
     * Published models that have a score for the benchmark, best first.
     * @return list<array{model:array<string,mixed>,score:array<string,mixed>}>
     */
    public static function ranking(string $benchmarkId, ?callable $filter = null): array
    {
        $bm = self::benchmark($benchmarkId);
        if (!$bm) {
            return [];
        }
        $rows = [];
        foreach (self::publicData()['models'] as $m) {
            $s = self::score((string)$m['id'], $benchmarkId);
            if ($s && is_numeric($s['value'] ?? null) && (!$filter || $filter($m))) {
                $rows[] = ['model' => $m, 'score' => $s];
            }
        }
        $dir = !empty($bm['higher_is_better']) ? -1 : 1;
        usort($rows, static fn($a, $b) => $dir * ($a['score']['value'] <=> $b['score']['value']));
        return $rows;
    }

    /** @param list<array<string,mixed>> $models @param list<array<string,mixed>> $useCases */
    public static function saveModels(array $models, ?array $useCases = null): void
    {
        $cur = self::models();
        self::write('ai-models', ['updated' => date('Y-m-d'), 'models' => array_values($models), 'use_cases' => $useCases ?? $cur['use_cases'], 'radar' => $cur['radar']]);
    }

    /** @param list<array<string,mixed>> $benchmarks @param list<array<string,mixed>> $scores */
    public static function saveBenchmarks(array $benchmarks, array $scores): void
    {
        self::write('ai-benchmarks', ['updated' => date('Y-m-d'), 'benchmarks' => array_values($benchmarks), 'scores' => array_values($scores)]);
    }

    private static function write(string $name, array $data): void
    {
        $f = self::path($name);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $tmp = $f . '.tmp';
        if ($json === false || file_put_contents($tmp, $json . "\n", LOCK_EX) === false || !rename($tmp, $f)) {
            throw new \RuntimeException('AI veri dosyası yazılamadı: data/' . $name . '.json');
        }
        self::$models = self::$bench = null;
        // Articles that embed AI tables and charts must be rebuilt from the new data.
        Cache::flush();
        PageCache::flush();
    }
}
