<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Audit;
use App\Core\Request;
use App\Core\Response;
use App\Services\AiData;
use App\Services\Sanitizer;

/** AI Data Management: models, benchmarks and scores stored in data/*.json. Saving clears the article caches. */
final class AiController extends AdminBase
{
    private const BACK = '/admin/ai';

    public function index(array $p): Response
    {
        $m = AiData::models();
        $b = AiData::benchmarks();
        return $this->view('ai', 'Yapay zekâ verileri', ['models' => $m['models'], 'benchmarks' => $b['benchmarks'], 'scores' => $b['scores'], 'updated' => AiData::updated()]);
    }

    private function id(string $key): string
    {
        return substr(preg_replace('/[^a-z0-9_\-.]/', '', strtolower(Request::str($key, 80))) ?? '', 0, 60);
    }

    private function num(string $key): ?float
    {
        $v = str_replace(',', '.', Request::str($key, 30));
        return $v !== '' && is_numeric($v) ? (float)$v : null;
    }

    public function saveModel(array $p): Response
    {
        $id = $this->id('id') ?: slugify(Request::str('name', 100));
        $name = Sanitizer::text(Request::str('name', 100));
        $provider = Sanitizer::text(Request::str('provider', 80));
        $type = Request::str('type', 20);
        if ($id === '' || $name === '' || $provider === '' || !isset(AiData::TYPES[$type])) {
            return $this->done('Kimlik, ad, sağlayıcı ve tür zorunludur.', self::BACK, false);
        }
        $priceIn = $this->num('price_in');
        $priceOut = $this->num('price_out');
        $priceUrl = Sanitizer::url(Request::str('price_source_url', 500));
        $priceDate = Request::str('price_date', 10);
        if (($priceIn !== null || $priceOut !== null) && ($priceUrl === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $priceDate))) {
            return $this->done('Fiyat girildiğinde kaynak URL ve tarih (YYYY-AA-GG) zorunludur.', self::BACK, false);
        }
        $ctx = $this->num('context_window');
        $model = [
            'id' => $id, 'name' => $name, 'provider' => $provider, 'type' => $type,
            'open' => Request::bool('open'), 'license' => Sanitizer::text(Request::str('license', 80)),
            'release' => Sanitizer::text(Request::str('release', 10)),
            'context_window' => $ctx !== null ? (int)$ctx : null,
            'modalities' => array_values(array_intersect(['text', 'image', 'audio', 'video', 'pdf'], (array)Request::input('modalities', []))),
            'price_in' => $priceIn, 'price_out' => $priceOut, 'price_source_url' => $priceUrl, 'price_date' => $priceIn !== null || $priceOut !== null ? $priceDate : '',
            'free_tier' => Request::bool('free_tier'),
            'best_for' => Sanitizer::text(Request::str('best_for', 200)),
            'url' => Sanitizer::url(Request::str('url', 500)),
            'published' => Request::bool('published'),
        ];
        $models = AiData::models()['models'];
        $found = false;
        foreach ($models as $i => $m) {
            if (($m['id'] ?? '') === $id) {
                $models[$i] = $model;
                $found = true;
            }
        }
        if (!$found) {
            $models[] = $model;
        }
        AiData::saveModels($models);
        Audit::log($found ? 'ai.model.update' : 'ai.model.create', 'ai_model', $id, ['published' => $model['published']]);
        return $this->done('Model kaydedildi; ilgili yazıların önbelleği temizlendi.', self::BACK);
    }

    public function deleteModel(array $p): Response
    {
        $id = $this->id('id');
        $b = AiData::benchmarks();
        AiData::saveBenchmarks($b['benchmarks'], array_filter($b['scores'], static fn($s) => ($s['model'] ?? '') !== $id));
        AiData::saveModels(array_filter(AiData::models()['models'], static fn($m) => ($m['id'] ?? '') !== $id));
        Audit::log('ai.model.delete', 'ai_model', $id);
        return $this->done('Model ve skorları silindi.', self::BACK);
    }

    public function saveBenchmark(array $p): Response
    {
        $id = str_replace(['-', '.'], '_', $this->id('id') ?: slugify(Request::str('name', 100)));
        $name = Sanitizer::text(Request::str('name', 100));
        $cat = Request::str('category', 20);
        if ($id === '' || $name === '' || !isset(AiData::CATEGORIES[$cat])) {
            return $this->done('Kimlik, ad ve kategori zorunludur.', self::BACK, false);
        }
        $bm = [
            'id' => $id, 'name' => $name, 'category' => $cat, 'unit' => Sanitizer::text(Request::str('unit', 10)),
            'higher_is_better' => Request::bool('higher_is_better'),
            'description' => Sanitizer::text(Request::str('description', 400)),
            'url' => Sanitizer::url(Request::str('url', 500)),
        ];
        $b = AiData::benchmarks();
        $found = false;
        foreach ($b['benchmarks'] as $i => $x) {
            if (($x['id'] ?? '') === $id) {
                $b['benchmarks'][$i] = $bm;
                $found = true;
            }
        }
        if (!$found) {
            $b['benchmarks'][] = $bm;
        }
        AiData::saveBenchmarks($b['benchmarks'], $b['scores']);
        Audit::log($found ? 'ai.benchmark.update' : 'ai.benchmark.create', 'ai_benchmark', $id);
        return $this->done('Kıyaslama kaydedildi.', self::BACK);
    }

    public function deleteBenchmark(array $p): Response
    {
        $id = str_replace(['-', '.'], '_', $this->id('id'));
        $b = AiData::benchmarks();
        AiData::saveBenchmarks(array_filter($b['benchmarks'], static fn($x) => ($x['id'] ?? '') !== $id), array_filter($b['scores'], static fn($s) => ($s['benchmark'] ?? '') !== $id));
        Audit::log('ai.benchmark.delete', 'ai_benchmark', $id);
        return $this->done('Kıyaslama ve skorları silindi.', self::BACK);
    }

    public function saveScore(array $p): Response
    {
        $model = $this->id('model');
        $bench = str_replace(['-', '.'], '_', $this->id('benchmark'));
        $value = $this->num('value');
        $url = Sanitizer::url(Request::str('source_url', 500));
        $date = Request::str('date', 10);
        $srcName = Sanitizer::text(Request::str('source_name', 100));
        $by = Request::str('reported_by') === 'vendor' ? 'vendor' : 'independent';
        $modelIds = array_column(AiData::models()['models'], 'id');
        if (!in_array($model, $modelIds, true) || !AiData::benchmark($bench)) {
            return $this->done('Model veya kıyaslama bulunamadı.', self::BACK, false);
        }
        if ($value === null) {
            return $this->done('Skor sayısal olmalıdır. Doğrulanamayan skoru girmeyin; tabloda “—” olarak görünür.', self::BACK, false);
        }
        if ($url === '' || $srcName === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || strtotime($date) === false || strtotime($date) > time() + 86400) {
            return $this->done('Kaynak adı, kaynak URL ve geçerli bir ölçüm tarihi (YYYY-AA-GG) zorunludur.', self::BACK, false);
        }
        $score = ['model' => $model, 'benchmark' => $bench, 'value' => $value, 'source_name' => $srcName, 'source_url' => $url, 'date' => $date, 'reported_by' => $by, 'note' => Sanitizer::text(Request::str('note', 200))];
        $b = AiData::benchmarks();
        $found = false;
        foreach ($b['scores'] as $i => $s) {
            if (($s['model'] ?? '') === $model && ($s['benchmark'] ?? '') === $bench) {
                $b['scores'][$i] = $score;
                $found = true;
            }
        }
        if (!$found) {
            $b['scores'][] = $score;
        }
        AiData::saveBenchmarks($b['benchmarks'], $b['scores']);
        Audit::log($found ? 'ai.score.update' : 'ai.score.create', 'ai_score', $model . ':' . $bench, ['value' => $value, 'source' => $url]);
        return $this->done('Skor kaydedildi; ilgili yazıların önbelleği temizlendi.', self::BACK);
    }

    public function deleteScore(array $p): Response
    {
        $model = $this->id('model');
        $bench = str_replace(['-', '.'], '_', $this->id('benchmark'));
        $b = AiData::benchmarks();
        AiData::saveBenchmarks($b['benchmarks'], array_filter($b['scores'], static fn($s) => !(($s['model'] ?? '') === $model && ($s['benchmark'] ?? '') === $bench)));
        Audit::log('ai.score.delete', 'ai_score', $model . ':' . $bench);
        return $this->done('Skor silindi.', self::BACK);
    }
}
