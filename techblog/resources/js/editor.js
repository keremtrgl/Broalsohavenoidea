// Block editor (Editor.js) for the post form, with custom tools for callouts, code, images,
// galleries, SVG illustrations, charts, FAQ and AI data widgets.
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import EditorjsList from '@editorjs/list';
import Table from '@editorjs/table';
import Quote from '@editorjs/quote';
import { $, $$, el, api, toast, fmt } from './util.js';

const ICON = (d) => `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${d}</svg>`;
let uid = 0;
let pickMedia = () => Promise.resolve(null);

/** Inline tool factory: wraps the selection in a simple tag (<code>, <mark>). */
function inlineTool(tag, title, icon) {
  return class {
    static get isInline() { return true; }
    static get title() { return title; }
    static get sanitize() { return { [tag]: {} }; }
    constructor({ api: edApi }) { this.api = edApi; this.button = null; }
    render() {
      this.button = el('button', { type: 'button', class: this.api.styles.inlineToolButton, 'aria-label': title });
      this.button.innerHTML = icon; // static, trusted icon markup
      return this.button;
    }
    surround(range) {
      if (!range) return;
      const wrapper = this.api.selection.findParentTag(tag.toUpperCase());
      if (wrapper) {
        const text = document.createTextNode(wrapper.textContent);
        wrapper.replaceWith(text);
        return;
      }
      const node = document.createElement(tag);
      node.append(range.extractContents());
      range.insertNode(node);
      this.api.selection.expandToTag(node);
    }
    checkState() {
      const active = Boolean(this.api.selection.findParentTag(tag.toUpperCase()));
      this.button?.classList.toggle(this.api.styles.inlineToolButtonActive, active);
      return active;
    }
  };
}

/**
 * Block tool factory driven by a field list. Field kinds:
 * text, textarea, code, select, checkbox, csv (comma list <-> array), json, media, repeat (list of sub-fields).
 */
function formTool({ title, icon, fields }) {
  return class {
    static get toolbox() { return { title, icon }; }
    static get isReadOnlySupported() { return true; }
    constructor({ data, readOnly }) { this.data = data || {}; this.readOnly = readOnly; this.getters = []; }

    field(f, value, getters) {
      const id = `tf${++uid}`;
      const wrap = el('div', { class: 'field' });
      if (f.kind === 'checkbox') {
        const input = el('input', { type: 'checkbox', id });
        input.checked = Boolean(value);
        wrap.append(el('label', { class: 'check', for: id }, input, ' ' + f.label));
        getters.push(() => [f.name, input.checked]);
        return wrap;
      }
      if (f.kind === 'repeat') {
        const box = el('div', {});
        const rows = [];
        const addRow = (rowData = {}) => {
          const rowGetters = [];
          const row = el('div', { class: 'rep' }, f.fields.map((sf) => this.field(sf, rowData[sf.name] ?? rowData, rowGetters)));
          const entry = { rowGetters, row };
          row.append(el('button', { type: 'button', class: 'btn btn-ghost btn-s', text: 'Bu öğeyi kaldır', onclick: () => { rows.splice(rows.indexOf(entry), 1); row.remove(); } }));
          rows.push(entry);
          box.append(row);
        };
        (Array.isArray(value) && value.length ? value : [{}]).forEach(addRow);
        wrap.append(el('span', { class: 'lbl', text: f.label }), box, el('button', { type: 'button', class: 'btn btn-ghost btn-s', text: f.addLabel || 'Öğe ekle', onclick: () => addRow() }));
        getters.push(() => [f.name, rows.map((r) => Object.assign({}, ...r.rowGetters.map((g) => { const [k, v] = g(); return typeof v === 'object' && v !== null && k === '' ? v : { [k]: v }; })))]);
        return wrap;
      }
      if (f.kind === 'media') {
        // Stores media_id, url, width and height of a library image.
        let cur = { media_id: value?.media_id || 0, url: value?.url || '', width: value?.width || 0, height: value?.height || 0 };
        const img = el('img', { class: 'thumb', alt: '', src: cur.url || 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' });
        const btn = el('button', { type: 'button', class: 'btn btn-ghost btn-s', text: 'Kütüphaneden seç', onclick: async () => {
          const m = await pickMedia();
          if (!m) return;
          cur = { media_id: m.id, url: m.url, width: m.width, height: m.height };
          img.src = m.thumb;
          const alt = wrap.parentElement?.querySelector('[data-alt]');
          if (alt && !alt.value) alt.value = m.alt;
        } });
        wrap.append(el('span', { class: 'lbl', text: f.label }), img, btn);
        getters.push(() => ['', cur]);
        return wrap;
      }
      let input;
      if (f.kind === 'select') {
        input = el('select', { id }, Object.entries(f.options).map(([k, label]) => el('option', { value: k, text: label })));
        input.value = value ?? Object.keys(f.options)[0];
      } else if (f.kind === 'textarea' || f.kind === 'code' || f.kind === 'json') {
        input = el('textarea', { id, class: f.kind === 'textarea' ? '' : 'codearea', spellcheck: f.kind === 'textarea' ? 'true' : 'false', rows: f.rows || 4 });
        input.value = f.kind === 'json' ? (value ? JSON.stringify(value, null, 1) : f.placeholder || '') : value ?? '';
        if (f.kind !== 'textarea') {
          input.addEventListener('keydown', (e) => { if (e.key === 'Tab' && !e.shiftKey) { e.preventDefault(); input.setRangeText('  ', input.selectionStart, input.selectionEnd, 'end'); } });
        }
      } else {
        input = el('input', { type: 'text', id, placeholder: f.placeholder || '' });
        input.value = f.kind === 'csv' ? (Array.isArray(value) ? value.join(', ') : '') : value ?? '';
        if (f.alt) input.dataset.alt = '1';
      }
      if (this.readOnly) input.disabled = true;
      // Keep Editor.js from handling keys typed inside form fields (Enter, Backspace, Tab...).
      input.addEventListener('keydown', (e) => e.stopPropagation());
      input.addEventListener('paste', (e) => e.stopPropagation());
      wrap.append(el('label', { for: id, text: f.label }), input);
      if (f.help) wrap.append(el('small', { text: f.help }));
      getters.push(() => {
        if (f.kind === 'csv') return [f.name, input.value.split(',').map((s) => s.trim()).filter(Boolean)];
        if (f.kind === 'json') {
          try { input.setCustomValidity(''); return [f.name, JSON.parse(input.value || 'null')]; } catch { input.setCustomValidity('Geçersiz JSON'); return [f.name, null]; }
        }
        return [f.name, input.value];
      });
      return wrap;
    }

    render() {
      this.getters = [];
      const flat = (f) => (f.kind === 'media' ? this.data : this.data[f.name]);
      return el('div', { class: 'tool' }, el('div', { class: 'tool-h', text: title }), fields.map((f) => this.field(f, flat(f), this.getters)));
    }

    save() {
      const out = {};
      for (const g of this.getters) {
        const [k, v] = g();
        if (k === '') Object.assign(out, v); else out[k] = v;
      }
      return out;
    }
  };
}

const LANGS = { plaintext: 'Düz metin', asm: 'Assembly', bash: 'Bash', c: 'C', cpp: 'C++', csharp: 'C#', css: 'CSS', dart: 'Dart', go: 'Go', haskell: 'Haskell', html: 'HTML', java: 'Java', javascript: 'JavaScript', json: 'JSON', kotlin: 'Kotlin', lua: 'Lua', php: 'PHP', python: 'Python', r: 'R', ruby: 'Ruby', rust: 'Rust', sql: 'SQL', swift: 'Swift', typescript: 'TypeScript' };

function tools(cfg) {
  const benchOptions = Object.fromEntries([['', '— seçin —'], ...cfg.benchmarks.map((b) => [b.id, b.name])]);
  const mediaItem = [{ name: 'media', kind: 'media', label: 'Görsel' }, { name: 'alt', kind: 'text', label: 'Alternatif metin (zorunlu)', alt: true }];
  return {
    header: { class: Header, inlineToolbar: true, config: { levels: [2, 3, 4], defaultLevel: 2, placeholder: 'Başlık' } },
    list: { class: EditorjsList, inlineToolbar: true, config: { defaultStyle: 'unordered' } },
    table: { class: Table, inlineToolbar: true, config: { withHeadings: true } },
    quote: { class: Quote, inlineToolbar: true, config: { quotePlaceholder: 'Alıntı', captionPlaceholder: 'Kaynak' } },
    code: { class: formTool({ title: 'Kod', icon: ICON('<path d="m8 8-4 4 4 4M16 8l4 4-4 4"/>'), fields: [
      { name: 'language', kind: 'select', label: 'Dil (sözdizimi renklendirme)', options: LANGS },
      { name: 'code', kind: 'code', label: 'Kod', rows: 8 }] }) },
    callout: { class: formTool({ title: 'Bilgi kutusu', icon: ICON('<circle cx="12" cy="12" r="9"/><path d="M12 8h.01M12 12v4"/>'), fields: [
      { name: 'type', kind: 'select', label: 'Tür', options: { info: 'Bilgi', tip: 'İpucu', warn: 'Dikkat', danger: 'Uyarı' } },
      { name: 'title', kind: 'text', label: 'Başlık' },
      { name: 'text', kind: 'textarea', label: 'Metin', help: 'Satır içi HTML: <b>, <i>, <code>, <a href>' }] }) },
    image: { class: formTool({ title: 'Görsel', icon: ICON('<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 16 5-5 4 4 3-3 6 6"/>'), fields: [
      ...mediaItem, { name: 'caption', kind: 'text', label: 'Açıklama yazısı' }] }) },
    gallery: { class: formTool({ title: 'Galeri', icon: ICON('<rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="8" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/><rect x="13" y="13" width="8" height="8" rx="1"/>'), fields: [
      { name: 'items', kind: 'repeat', label: 'Görseller', addLabel: 'Görsel ekle', fields: mediaItem },
      { name: 'caption', kind: 'text', label: 'Açıklama yazısı' }] }) },
    svg: { class: formTool({ title: 'SVG / animasyon', icon: ICON('<path d="M4 18c4-12 12-12 16 0"/><circle cx="4" cy="18" r="2"/><circle cx="20" cy="18" r="2"/>'), fields: [
      { name: 'code', kind: 'code', label: 'SVG kodu', rows: 10, help: 'Kaydederken temizlenir (betik ve olay öznitelikleri silinir). Renkler için s-*/l-* sınıflarını, animasyon için data-a özniteliğini kullanın; role="img" ve aria-label eklemeyi unutmayın.' },
      { name: 'caption', kind: 'text', label: 'Açıklama yazısı' }] }) },
    chart: { class: formTool({ title: 'Grafik', icon: ICON('<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>'), fields: [
      { name: 'type', kind: 'select', label: 'Tür', options: { bar: 'Çubuk', line: 'Çizgi', radar: 'Radar', scatter: 'Dağılım', doughnut: 'Halka' } },
      { name: 'title', kind: 'text', label: 'Başlık' },
      { name: 'labels', kind: 'csv', label: 'Kategori etiketleri (virgülle)' },
      { name: 'datasets', kind: 'json', label: 'Veri serileri (JSON)', rows: 5, placeholder: '[{"label": "Seri adı", "data": [1, 2, 3]}]', help: 'Dağılım grafiğinde: {"data": [{"x": 1, "y": 2, "label": "Ad"}]}' },
      { name: 'unit', kind: 'text', label: 'Birim' }, { name: 'xTitle', kind: 'text', label: 'X ekseni başlığı' }, { name: 'yTitle', kind: 'text', label: 'Y ekseni başlığı' },
      { name: 'horizontal', kind: 'checkbox', label: 'Yatay çubuklar' }, { name: 'stacked', kind: 'checkbox', label: 'Yığılmış' }, { name: 'log', kind: 'checkbox', label: 'Logaritmik ölçek' },
      { name: 'source', kind: 'text', label: 'Veri kaynağı (zorunlu tutun)' }, { name: 'sourceUrl', kind: 'text', label: 'Kaynak URL' },
      { name: 'date', kind: 'text', label: 'Veri tarihi' }, { name: 'note', kind: 'text', label: 'Not (ör. “değerler yaklaşıktır”)' }] }) },
    faq: { class: formTool({ title: 'SSS', icon: ICON('<circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1 1-1 1.7M12 17h.01"/>'), fields: [
      { name: 'items', kind: 'repeat', label: 'Sorular', addLabel: 'Soru ekle', fields: [{ name: 'q', kind: 'text', label: 'Soru' }, { name: 'a', kind: 'textarea', label: 'Yanıt', rows: 3 }] }] }) },
    ai: { class: formTool({ title: 'YZ veri bileşeni', icon: ICON('<path d="M12 3l1.8 4.6L18.5 9l-4.7 1.4L12 15l-1.8-4.6L5.5 9l4.7-1.4z"/>'), fields: [
      { name: 'widget', kind: 'select', label: 'Bileşen', options: { 'models-table': 'Model karşılaştırma tablosu', 'benchmark-table': 'Kıyaslama sıralama tablosu', 'benchmark-chart': 'Kıyaslama çubuk grafiği', radar: 'Güçlü yönler radar grafiği', scatter: 'Fiyat / performans dağılım grafiği', picker: '“Hangi YZ bana uygun?” aracı', 'decision-table': 'Karar tablosu' } },
      { name: 'benchmark', kind: 'select', label: 'Kıyaslama (tablo / grafik için)', options: benchOptions },
      { name: 'model_type', kind: 'select', label: 'Model türü (model tablosu için)', options: { llm: 'Dil modeli', image: 'Görsel', video: 'Video', audio: 'Ses' } },
      { name: 'benchmarks', kind: 'csv', label: 'Model tablosundaki skor sütunları (kıyaslama kimlikleri, virgülle)' }] }) },
    inlineCode: { class: inlineTool('code', 'Satır içi kod', ICON('<path d="m8 8-4 4 4 4M16 8l4 4-4 4"/>')) },
    marker: { class: inlineTool('mark', 'Vurgula', ICON('<path d="m5 19 2-6L17 3l4 4-10 10z"/><path d="M3 21h8"/>')) },
  };
}

const I18N = { messages: {
  ui: { blockTunes: { toggler: { 'Click to tune': 'Ayarlar', 'or drag to move': 'veya sürükleyip taşıyın' } }, inlineToolbar: { converter: { 'Convert to': 'Dönüştür' } }, toolbar: { toolbox: { Add: 'Blok ekle' } }, popover: { Filter: 'Süz', 'Nothing found': 'Bulunamadı', 'Convert to': 'Dönüştür' } },
  toolNames: { Text: 'Paragraf', Heading: 'Başlık', 'Unordered List': 'Madde listesi', 'Ordered List': 'Numaralı liste', Checklist: 'Kontrol listesi', Table: 'Tablo', Quote: 'Alıntı', Bold: 'Kalın', Italic: 'İtalik', Link: 'Bağlantı' },
  blockTunes: { delete: { Delete: 'Sil', 'Click to delete': 'Silmek için tekrar tıklayın' }, moveUp: { 'Move up': 'Yukarı taşı' }, moveDown: { 'Move down': 'Aşağı taşı' } },
} };

export async function init({ ask }) {
  const cfg = JSON.parse($('#editor-cfg').textContent);
  const form = $('#post-form');
  let dirty = false;

  // Media picker shared by block tools and the cover / OG image fields.
  const mdlg = $('#media-dlg');
  pickMedia = () => new Promise((resolve) => {
    let done = false;
    const finish = (m) => { if (!done) { done = true; resolve(m); mdlg.close(); } };
    mdlg.addEventListener('close', () => finish(null), { once: true });
    const grid = $('#media-grid');
    grid.replaceChildren(el('p', { class: 'muted', text: 'Yükleniyor…' }));
    mdlg.showModal();
    api('/admin/media/list').then((r) => {
      if (!r.ok || !r.items.length) { grid.replaceChildren(el('p', { class: 'muted', text: 'Kütüphanede görsel yok. Önce Medya sayfasından yükleyin.' })); return; }
      grid.replaceChildren(...r.items.map((m) => el('div', { class: 'mitem' },
        el('button', { type: 'button', class: 'pick', 'aria-label': `Seç: ${m.alt}`, onclick: () => finish(m) },
          el('img', { src: m.thumb, alt: m.alt, loading: 'lazy' }), el('div', { class: 'mi' }, el('small', { class: 'muted', text: `${m.alt} · ${m.width}×${m.height}` }))))));
    });
  });
  $$('[data-media-pick]').forEach((b) => b.addEventListener('click', async () => {
    const m = await pickMedia();
    if (!m) return;
    $(b.dataset.mediaPick).value = m.url;
    $(b.dataset.mediaPick).dispatchEvent(new Event('input'));
    if (b.dataset.mediaAlt && !$(b.dataset.mediaAlt).value) $(b.dataset.mediaAlt).value = m.alt;
    dirty = true;
  }));

  const editor = new EditorJS({
    holder: 'editor',
    data: cfg.content,
    tools: tools(cfg),
    inlineToolbar: ['bold', 'italic', 'link', 'inlineCode', 'marker'],
    placeholder: 'Yazmaya başlayın veya blok eklemek için + düğmesine basın…',
    i18n: I18N,
    minHeight: 200,
    onChange: () => { dirty = true; },
  });
  await editor.isReady;

  // Slug: generated from the title until the author edits it.
  const title = $('#title'), slug = $('#slug'), slugPrev = $('#slug-prev');
  const TR = { ç: 'c', ğ: 'g', ı: 'i', ö: 'o', ş: 's', ü: 'u', İ: 'i' };
  const slugify = (s) => s.replace(/[çğıöşüİ]/g, (ch) => TR[ch]).toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 120);
  title.addEventListener('input', () => { if (slug.dataset.auto === '1') { slug.value = slugify(title.value); slugPrev.textContent = slug.value || '…'; } });
  slug.addEventListener('input', () => { slug.dataset.auto = '0'; slug.value = slugify(slug.value.replace(/\s/g, '-')) + (slug.value.endsWith('-') ? '-' : ''); slugPrev.textContent = slug.value || '…'; });

  const md = $('#meta_description'), mdCount = $('#md-count');
  const count = () => { mdCount.textContent = `${md.value.length} karakter`; };
  md.addEventListener('input', count);
  count();

  const cover = $('#cover_image'), coverPrev = $('#cover-prev');
  cover.addEventListener('input', () => {
    coverPrev.replaceChildren(/^\/(uploads|assets)\//.test(cover.value) ? el('img', { src: cover.value, alt: '' }) : 'Görsel seçilmedi');
  });
  form.addEventListener('input', () => { dirty = true; });

  const collect = async () => {
    const data = await editor.save();
    $('#content_json').value = JSON.stringify(data);
    return data;
  };

  // Save without leaving the page (Ctrl+S or the Save button).
  let saving = false;
  const save = async () => {
    if (saving) return;
    if (!title.value.trim()) { toast('Başlık zorunludur.', 'error'); title.focus(); return; }
    if (cover.value && !$('#cover_alt').value.trim()) { toast('Kapak görseli için alternatif metin zorunludur.', 'error'); $('#cover_alt').focus(); return; }
    saving = true;
    $('#save-btn').disabled = true;
    await collect();
    const r = await api('/admin/posts/save', { method: 'POST', form: new FormData(form) });
    saving = false;
    $('#save-btn').disabled = false;
    if (!r.ok) { toast(r.error || 'Kaydedilemedi.', 'error'); return; }
    dirty = false;
    toast(r.message);
    if (r.redirect) { location.href = r.redirect; return; }
    slug.value = r.slug;
    slugPrev.textContent = r.slug;
  };
  form.addEventListener('submit', (e) => { e.preventDefault(); save(); });

  const preview = async () => {
    await collect();
    const r = await api('/admin/posts/preview', { method: 'POST', body: { content_json: $('#content_json').value } });
    if (!r.ok) { toast(r.error || 'Önizleme oluşturulamadı.', 'error'); return; }
    const body = $('#preview-body');
    // The HTML comes from the server-side renderer, which escapes and sanitizes every block.
    body.innerHTML = r.html;
    $('#editor-stats').textContent = `${fmt(r.words)} sözcük · tahmini okuma ${r.minutes} dk`;
    $('#preview-dlg').showModal();
    if (body.querySelector('[data-chart], [data-ai-chart], [data-ai-picker]')) import('./charts.js').then((m) => m.init());
    if (body.querySelector('.fx [data-a]')) import('./anim.js').then((m) => m.init());
    if (body.querySelector('pre code')) import('./highlight.js').then((m) => m.init(body));
  };
  $('#preview-btn').addEventListener('click', preview);

  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') { e.preventDefault(); save(); }
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'p') { e.preventDefault(); preview(); }
  });
  addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
}
