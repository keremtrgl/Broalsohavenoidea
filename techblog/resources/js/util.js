// Small shared helpers.
export const $ = (sel, root = document) => root.querySelector(sel);
export const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];
export const reducedMotion = () => matchMedia('(prefers-reduced-motion: reduce)').matches;

/** Create an element; text is always set with textContent (never innerHTML). */
export function el(tag, attrs = {}, ...children) {
  const node = document.createElement(tag);
  for (const [k, v] of Object.entries(attrs)) {
    if (v === false || v == null) continue;
    if (k === 'class') node.className = v;
    else if (k === 'text') node.textContent = v;
    else if (k.startsWith('on')) node.addEventListener(k.slice(2), v);
    else node.setAttribute(k, v === true ? '' : v);
  }
  for (const c of children.flat()) if (c != null) node.append(c);
  return node;
}

export function toast(message, type = 'info') {
  const box = $('#toasts');
  if (!box) return;
  const t = el('div', { class: `toast toast-${type}`, text: message });
  box.append(t);
  setTimeout(() => t.remove(), type === 'error' ? 7000 : 4000);
}

let csrf = '';
export const setCsrf = (t) => { csrf = t || ''; };
export const getCsrf = () => csrf;

/** fetch() wrapper for the JSON API. Adds the CSRF token to non-GET requests. */
export async function api(url, { method = 'GET', body, form } = {}) {
  const headers = { Accept: 'application/json', 'X-Requested-With': 'fetch' };
  const init = { method, headers, credentials: 'same-origin' };
  if (method !== 'GET') headers['X-CSRF-Token'] = csrf;
  if (form) init.body = form;
  else if (body !== undefined) {
    headers['Content-Type'] = 'application/json';
    init.body = JSON.stringify(body);
  }
  let res;
  try {
    res = await fetch(url, init);
  } catch {
    return { ok: false, error: 'Sunucuya ulaşılamadı. Bağlantınızı kontrol edin.' };
  }
  if (res.status === 204) return { ok: true };
  try {
    const data = await res.json();
    if (!res.ok && data.ok === undefined) data.ok = false;
    return data;
  } catch {
    return { ok: false, error: `Beklenmeyen yanıt (${res.status}).` };
  }
}

export const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
export const fmt = (n, max = 2) => new Intl.NumberFormat('tr-TR', { maximumFractionDigits: max }).format(n);
