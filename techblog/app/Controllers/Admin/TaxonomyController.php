<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Audit;
use App\Core\Request;
use App\Core\Response;
use App\Models\Taxonomy;
use App\Services\Sanitizer;

/** Categories and tags: CRUD plus drag-and-drop ordering. */
final class TaxonomyController extends AdminBase
{
    public const ICONS = ['chip', 'code', 'network', 'ai', 'device', 'globe', 'layers', 'book'];

    private function kind(): string
    {
        return str_starts_with(Request::path(), '/admin/tags') ? 'tag' : 'category';
    }

    private function base(): string
    {
        return $this->kind() === 'tag' ? '/admin/tags' : '/admin/categories';
    }

    public function index(array $p): Response
    {
        $kind = $this->kind();
        return $this->view('taxonomy', $kind === 'tag' ? 'Etiketler' : 'Kategoriler', [
            'kind' => $kind, 'base' => $this->base(), 'items' => Taxonomy::all($kind), 'icons' => self::ICONS,
            'active' => $kind === 'tag' ? 'tags' : 'categories',
        ]);
    }

    public function save(array $p): Response
    {
        $kind = $this->kind();
        $id = Request::int('id');
        if ($id > 0 && !Taxonomy::find($kind, $id)) {
            return App::notFound();
        }
        $name = Sanitizer::text(Request::str('name', 100));
        if (mb_strlen($name) < 2) {
            return $this->done('Ad en az 2 karakter olmalı.', $this->base(), false);
        }
        $data = ['name' => $name, 'slug' => Taxonomy::uniqueSlug($kind, Request::str('slug', 120) ?: $name, $id)];
        if ($kind === 'category') {
            $data['description'] = Sanitizer::text(Request::str('description', 300));
            $icon = Request::str('icon', 30);
            $data['icon'] = in_array($icon, self::ICONS, true) ? $icon : 'chip';
        }
        $newId = Taxonomy::save($kind, $data, $id);
        $this->contentChanged();
        Audit::log($kind . ($id > 0 ? '.update' : '.create'), $kind, $newId, ['name' => $name]);
        return $this->done('Kaydedildi.', $this->base());
    }

    public function reorder(array $p): Response
    {
        $ids = array_values(array_filter(array_map('intval', (array)Request::input('ids', []))));
        Taxonomy::reorder($this->kind(), $ids);
        $this->contentChanged();
        Audit::log($this->kind() . '.reorder', $this->kind());
        return Response::json(['ok' => true, 'message' => 'Sıralama kaydedildi.']);
    }

    public function delete(array $p): Response
    {
        $kind = $this->kind();
        $item = Taxonomy::find($kind, (int)$p['id']);
        if (!$item) {
            return App::notFound();
        }
        Taxonomy::delete($kind, (int)$item['id']);
        $this->contentChanged();
        Audit::log($kind . '.delete', $kind, (int)$item['id'], ['name' => $item['name']]);
        return $this->done('Silindi.', $this->base());
    }
}
