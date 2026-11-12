// Public site entry point. Feature modules are loaded only on pages that need them.
import { $, $$, el, api, setCsrf, toast } from './util.js';

const root = document.documentElement;

// ---- Theme (system preference by default, manual toggle remembered) ----
function setTheme(theme, persist) {
  root.dataset.theme = theme;
  if (persist) {
    try { localStorage.setItem('theme', theme); } catch { /* storage unavailable */ }
  }
  document.dispatchEvent(new CustomEvent('themechange', { detail: theme }));
}
$$('[data-theme-toggle]').forEach((b) => b.addEventListener('click', () => setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark', true)));
matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  let saved = null;
  try { saved = localStorage.getItem('theme'); } catch { /* ignore */ }
  if (!saved) setTheme(e.matches ? 'dark' : 'light', false);
});

// ---- Mobile menu ----
const menuBtn = $('[data-menu-toggle]');
const nav = $('#nav');
if (menuBtn && nav) {
  menuBtn.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', String(open));
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('open')) {
      nav.classList.remove('open');
      menuBtn.setAttribute('aria-expanded', 'false');
      menuBtn.focus();
    }
  });
}

// ---- Instant search ----
const dlg = $('#search-dlg');
if (dlg && typeof dlg.showModal === 'function') {
  const input = $('#search-q');
  const list = $('#search-res');
  const hint = (text) => list.replaceChildren(el('li', {}, el('span', { class: 'hint', text })));
  let timer = 0;
  let seq = 0;
  const open = () => { dlg.showModal(); input.focus(); input.select(); };
  $$('[data-search-open]').forEach((b) => b.addEventListener('click', open));
  $$('[data-search-close]').forEach((b) => b.addEventListener('click', () => dlg.close()));
  dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); });
  document.addEventListener('keydown', (e) => {
    const typing = /^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement?.tagName || '') || document.activeElement?.isContentEditable;
    if ((e.key === '/' && !typing) || (e.key.toLowerCase() === 'k' && (e.ctrlKey || e.metaKey))) {
      e.preventDefault();
      open();
    }
  });
  input.addEventListener('input', () => {
    clearTimeout(timer);
    const q = input.value.trim();
    if (q.length < 2) { hint('En az iki harf yazın. Enter ile tüm sonuçları açabilirsiniz.'); return; }
    timer = setTimeout(async () => {
      const mine = ++seq;
      const data = await api(`/api/search?q=${encodeURIComponent(q)}`);
      if (mine !== seq) return;
      if (!data.ok) { hint(data.error || 'Arama yapılamadı.'); return; }
      if (!data.results.length) { hint('Sonuç bulunamadı.'); return; }
      list.replaceChildren(...data.results.map((r) => el('li', {}, el('a', { href: r.url },
        el('strong', { text: r.title }),
        el('span', { text: `${r.category ? r.category + ' · ' : ''}${r.minutes} dk · ${r.excerpt}` })))));
    }, 180);
  });
} else {
  $$('[data-search-open]').forEach((b) => b.addEventListener('click', () => { location.href = '/search'; }));
}

// ---- Signed-in state (cached pages are identical for everyone; this fills in the user) ----
export const me = (async () => {
  if (!document.cookie.split('; ').some((c) => c.startsWith('tb_auth=1'))) return null;
  const data = await api('/api/me');
  if (!data.user) return null;
  setCsrf(data.csrf);
  const slot = $('[data-auth-slot]');
  if (slot) {
    const logout = el('button', { class: 'linkbtn', type: 'button', text: 'Çıkış', onclick: async () => { await api('/logout', { method: 'POST' }); location.reload(); } });
    const avatar = data.user.avatar
      ? el('img', { src: data.user.avatar, alt: '', width: 32, height: 32, referrerpolicy: 'no-referrer' })
      : el('span', { class: 'avatar avatar-l', text: (data.user.name || '?').slice(0, 1).toUpperCase() });
    slot.replaceChildren(el('span', { class: 'user-chip' }, avatar, el('span', { text: data.user.name }),
      data.user.staff ? el('a', { href: '/admin', text: 'Panel' }) : null, logout));
  }
  return data.user;
})();

// ---- Cookie consent (KVKK/GDPR): analytics loads only after an explicit "allow") ----
const cfg = JSON.parse($('#site-cfg')?.textContent || '{}');
const banner = $('#cookie');
const getConsent = () => { try { return localStorage.getItem('consent'); } catch { return null; } };
function loadAnalytics() {
  if (window.__analytics) return;
  window.__analytics = true;
  if (cfg.ga4) {
    window.dataLayer = window.dataLayer || [];
    window.gtag = function gtag() { window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', cfg.ga4, { anonymize_ip: true });
    document.head.append(el('script', { src: `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(cfg.ga4)}`, async: true }));
  }
  if (cfg.script) document.head.append(el('script', { src: cfg.script, defer: true }));
}
if (banner) {
  const hasAnalytics = Boolean(cfg.ga4 || cfg.script);
  const consent = getConsent();
  if (consent === 'allow' && hasAnalytics) loadAnalytics();
  if (!consent && hasAnalytics) banner.hidden = false;
  $$('[data-consent]', banner).forEach((b) => b.addEventListener('click', () => {
    try { localStorage.setItem('consent', b.dataset.consent); } catch { /* ignore */ }
    banner.hidden = true;
    if (b.dataset.consent === 'allow' && hasAnalytics) loadAnalytics();
    toast(b.dataset.consent === 'allow' ? 'Analitik çerezlere izin verildi.' : 'Yalnızca zorunlu çerezler kullanılacak.');
  }));
  $$('[data-cookie-open]').forEach((b) => b.addEventListener('click', () => { banner.hidden = false; banner.querySelector('button')?.focus(); }));
}

// ---- Lazy feature modules ----
if ($('.fx [data-a]')) import('./anim.js').then((m) => m.init());
if ($('[data-chart], [data-ai-chart], [data-ai-picker]')) import('./charts.js').then((m) => m.init());
if ($('pre code')) {
  const io = new IntersectionObserver((entries) => {
    if (entries.some((e) => e.isIntersecting)) { io.disconnect(); import('./highlight.js').then((m) => m.init()); }
  }, { rootMargin: '600px' });
  $$('pre code').forEach((c) => io.observe(c));
}
if ($('article[data-post-id]')) import('./post.js').then((m) => m.init(me));
