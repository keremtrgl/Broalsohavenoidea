// Admin panel behaviour: theme, toasts, confirm dialogs, shortcuts, sortable lists, uploads.
import { $, $$, el, api, setCsrf, toast } from './util.js';

setCsrf($('meta[name=csrf]')?.content);
const root = document.documentElement;

// Theme
$$('[data-theme-toggle]').forEach((b) => b.addEventListener('click', () => {
  root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
  try { localStorage.setItem('theme', root.dataset.theme); } catch { /* ignore */ }
  document.dispatchEvent(new CustomEvent('themechange'));
}));

// Flash messages from the previous request
try {
  JSON.parse($('#flash')?.textContent || '[]').forEach((f) => toast(f.message, f.type === 'error' ? 'error' : 'info'));
} catch { /* ignore */ }

// Sidebar on small screens
const side = $('#side');
$('[data-side-toggle]')?.addEventListener('click', (e) => {
  const open = side.classList.toggle('open');
  e.currentTarget.setAttribute('aria-expanded', String(open));
});

// Dialog helpers
const openDlg = (d) => { if (d && !d.open) d.showModal(); };
document.addEventListener('click', (e) => {
  const closer = e.target.closest('[data-close]');
  if (closer) closer.closest('dialog')?.close();
  if (e.target instanceof HTMLDialogElement) e.target.close(); // click on the backdrop
  const opener = e.target.closest('[data-open]');
  if (opener) {
    const form = opener.dataset.reset ? $(opener.dataset.reset) : opener.dataset.fillForm ? $(opener.dataset.fillForm) : null;
    if (form) {
      form.reset();
      if (opener.dataset.values) fillForm(form, JSON.parse(opener.dataset.values));
    }
    openDlg($(opener.dataset.open));
  }
});

function fillForm(form, values) {
  for (const field of form.elements) {
    if (!field.name || field.name === '_csrf') continue;
    const key = field.name.replace(/\[\]$/, '');
    const v = values[key];
    if (field.type === 'checkbox') field.checked = Array.isArray(v) ? v.includes(field.value) : Boolean(v);
    else if (v !== undefined && v !== null) field.value = v;
    else field.value = '';
  }
}

// Confirmation dialog for destructive actions
const cdlg = $('#confirm-dlg');
let pending = null;
function ask(text, onYes) {
  $('#confirm-text').textContent = text;
  pending = onYes;
  openDlg(cdlg);
  $('[data-confirm-no]', cdlg).focus();
}
$('[data-confirm-yes]', cdlg).addEventListener('click', () => { cdlg.close(); const fn = pending; pending = null; fn?.(); });
$('[data-confirm-no]', cdlg).addEventListener('click', () => { pending = null; cdlg.close(); });
document.addEventListener('submit', (e) => {
  const form = e.target;
  const btn = e.submitter;
  const text = btn?.dataset.confirmBtn || (btn?.hasAttribute('formaction') ? null : form.dataset.confirm);
  if (!text || form.dataset.confirmed === '1') { delete form.dataset.confirmed; return; }
  e.preventDefault();
  ask(text, () => { form.dataset.confirmed = '1'; form.requestSubmit(btn); });
});

// Keyboard shortcuts
const kdlg = $('#keys-dlg');
$('[data-keys-open]')?.addEventListener('click', () => openDlg(kdlg));
let gPressed = 0;
document.addEventListener('keydown', (e) => {
  const t = document.activeElement;
  const typing = /^(INPUT|TEXTAREA|SELECT)$/.test(t?.tagName || '') || t?.isContentEditable;
  if (typing || e.ctrlKey || e.metaKey || e.altKey || $('dialog[open]')) return;
  const go = { d: '/admin', p: '/admin/posts', m: '/admin/media', c: '/admin/comments' };
  if (gPressed && Date.now() - gPressed < 1200 && go[e.key]) { location.href = go[e.key]; return; }
  if (e.key === 'g') { gPressed = Date.now(); return; }
  if (e.key === 'n') location.href = '/admin/posts/new';
  else if (e.key === '?') openDlg(kdlg);
  else if (e.key === '/') { const s = $('[data-search-focus]'); if (s) { e.preventDefault(); s.focus(); } }
});

// Drag-and-drop ordering (with keyboard-accessible up/down buttons)
$$('[data-sortable]').forEach((list) => {
  let dragged = null;
  const save = async () => {
    const r = await api(list.dataset.sortable, { method: 'POST', body: { ids: $$('li', list).map((li) => Number(li.dataset.id)) } });
    toast(r.ok ? r.message : r.error || 'Sıralama kaydedilemedi.', r.ok ? 'info' : 'error');
  };
  list.addEventListener('dragstart', (e) => { dragged = e.target.closest('li'); dragged?.classList.add('drag'); e.dataTransfer.effectAllowed = 'move'; });
  list.addEventListener('dragover', (e) => {
    e.preventDefault();
    const over = e.target.closest('li');
    if (!dragged || !over || over === dragged) return;
    const r = over.getBoundingClientRect();
    over.parentNode.insertBefore(dragged, e.clientY > r.top + r.height / 2 ? over.nextSibling : over);
  });
  list.addEventListener('dragend', () => { dragged?.classList.remove('drag'); dragged = null; save(); });
  list.addEventListener('click', (e) => {
    const b = e.target.closest('[data-move]');
    if (!b) return;
    const li = b.closest('li');
    const sib = b.dataset.move === '-1' ? li.previousElementSibling : li.nextElementSibling;
    if (!sib) return;
    li.parentNode.insertBefore(li, b.dataset.move === '-1' ? sib : sib.nextSibling);
    b.focus();
    save();
  });
});

// Taxonomy edit: copy a row into the form
$$('[data-fill]').forEach((b) => b.addEventListener('click', () => {
  const form = $('#tax-form');
  fillForm(form, JSON.parse(b.dataset.fill));
  $('#tax-form-title').textContent = 'Düzenle: ' + form.elements.name.value;
  form.elements.name.focus();
}));
$('[data-reset-id]')?.addEventListener('click', (e) => { e.target.form.elements.id.value = '0'; $('#tax-form-title').textContent = 'Yeni kayıt'; });

// Bulk selection
$('[data-check-all]')?.addEventListener('change', (e) => $$('input[name="ids[]"]').forEach((c) => { c.checked = e.target.checked; }));

// Client-side table filter
$$('[data-filter]').forEach((input) => input.addEventListener('input', () => {
  const q = input.value.toLocaleLowerCase('tr');
  $$('tr', $(input.dataset.filter)).forEach((tr) => { tr.hidden = q !== '' && !tr.textContent.toLocaleLowerCase('tr').includes(q); });
}));

// Repeatable rows (menu editor)
document.addEventListener('click', (e) => {
  const add = e.target.closest('[data-row-add]');
  if (add) {
    const box = $(add.dataset.rowAdd);
    const row = box.lastElementChild.cloneNode(true);
    const n = box.children.length;
    $$('input', row).forEach((i, k) => { i.value = ''; i.id = `${k ? 'mu' : 'ml'}-${n}`; });
    $$('label', row).forEach((l, k) => { l.htmlFor = `${k ? 'mu' : 'ml'}-${n}`; });
    box.append(row);
    $('input', row).focus();
  }
  const rm = e.target.closest('[data-row-remove]');
  if (rm) {
    const row = rm.parentElement;
    if (row.parentElement.children.length > 1) row.remove(); else $$('input', row).forEach((i) => { i.value = ''; });
  }
});

// Media library: drag-and-drop upload
const drop = $('#drop');
if (drop) {
  const input = $('#file-in');
  const status = $('#upload-status');
  const upload = async (files) => {
    const alt = $('#upload-alt').value.trim();
    if (alt.length < 3) { toast('Önce alternatif metni yazın (zorunlu).', 'error'); $('#upload-alt').focus(); return; }
    let ok = 0;
    for (const [i, file] of [...files].entries()) {
      status.textContent = `Yükleniyor: ${file.name} (${i + 1}/${files.length})…`;
      const form = new FormData();
      form.append('file', file);
      form.append('alt', files.length > 1 ? `${alt} (${i + 1})` : alt);
      const r = await api('/admin/media/upload', { method: 'POST', form });
      if (r.ok) ok++; else toast(`${file.name}: ${r.error || 'yüklenemedi'}`, 'error');
    }
    status.textContent = '';
    if (ok) { toast(`${ok} görsel yüklendi.`); setTimeout(() => location.reload(), 700); }
  };
  ['dragenter', 'dragover'].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.add('over'); }));
  ['dragleave', 'drop'].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.remove('over'); }));
  drop.addEventListener('drop', (e) => { if (e.dataTransfer.files.length) upload(e.dataTransfer.files); });
  input.addEventListener('change', () => { if (input.files.length) upload(input.files); input.value = ''; });
  drop.addEventListener('submit', (e) => e.preventDefault());
}

// Lazy modules
if ($('[data-chart]')) import('./charts.js').then((m) => m.init());
if ($('#editor')) import('./editor.js').then((m) => m.init({ ask }));
