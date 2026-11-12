// Article page behaviour: reading progress, active table of contents, copy/share, comments, view counter.
import { $, $$, el, api, toast } from './util.js';

function progress() {
  const bar = $('#progress');
  const content = $('#content');
  if (!bar || !content) return;
  let ticking = false;
  const update = () => {
    const rect = content.getBoundingClientRect();
    const total = rect.height - innerHeight * 0.6;
    const done = Math.min(1, Math.max(0, -rect.top / (total > 0 ? total : 1)));
    bar.style.transform = `scaleX(${done})`;
    ticking = false;
  };
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
  update();
}

function toc() {
  const links = $$('#toc a');
  if (!links.length) return;
  const map = new Map(links.map((a) => [decodeURIComponent(a.hash.slice(1)), a]));
  const heads = $$('#content h2[id], #content h3[id]').filter((h) => map.has(h.id));
  let current = null;
  const setActive = (id) => {
    if (id === current) return;
    current = id;
    links.forEach((a) => { a.classList.remove('on'); a.removeAttribute('aria-current'); });
    const a = map.get(id);
    if (a) {
      a.classList.add('on');
      a.setAttribute('aria-current', 'location');
      const box = $('#toc');
      if (box.scrollHeight > box.clientHeight) box.scrollTop = a.offsetTop - box.clientHeight / 2;
    }
  };
  const io = new IntersectionObserver(() => {
    let active = heads[0]?.id;
    for (const h of heads) {
      if (h.getBoundingClientRect().top <= 120) active = h.id; else break;
    }
    setActive(active);
  }, { rootMargin: '-100px 0px -60% 0px', threshold: [0, 1] });
  heads.forEach((h) => io.observe(h));
}

function copyAndShare() {
  document.addEventListener('click', async (e) => {
    const copy = e.target.closest('[data-copy]');
    if (copy) {
      const code = copy.closest('.code')?.querySelector('code')?.textContent || '';
      try { await navigator.clipboard.writeText(code); copy.textContent = 'Kopyalandı'; } catch { copy.textContent = 'Kopyalanamadı'; }
      setTimeout(() => { copy.textContent = 'Kopyala'; }, 1800);
    }
    const link = e.target.closest('[data-copy-link]');
    if (link) {
      try { await navigator.clipboard.writeText(link.dataset.copyLink); toast('Bağlantı kopyalandı.'); } catch { toast('Bağlantı kopyalanamadı.', 'error'); }
    }
  });
  const native = $('[data-native-share]');
  if (native && navigator.share) {
    native.hidden = false;
    native.addEventListener('click', () => navigator.share({ title: document.title, url: location.href }).catch(() => {}));
  }
}

function avatar(c) {
  return c.avatar
    ? el('img', { class: 'avatar', src: c.avatar, alt: '', width: 38, height: 38, loading: 'lazy', referrerpolicy: 'no-referrer' })
    : el('span', { class: 'avatar avatar-l', text: (c.name || '?').slice(0, 1).toUpperCase(), 'aria-hidden': 'true' });
}

async function comments(postId, user) {
  const list = $('#comment-list');
  const slot = $('#comment-form-slot');
  if (!list || !slot) return;

  const load = async () => {
    const data = await api(`/api/comments?post_id=${postId}`);
    if (!data.ok) { list.replaceChildren(el('p', { class: 'hint', text: 'Yorumlar yüklenemedi.' })); return; }
    if (!data.comments.length) { list.replaceChildren(el('p', { class: 'hint', text: 'Henüz yorum yok. İlk yorumu siz yazın.' })); return; }
    const node = (c, replies) => el('div', { class: 'comment', id: `c${c.id}` }, avatar(c),
      el('div', { class: 'comment-body' },
        el('div', { class: 'comment-head' }, el('strong', { text: c.name }), c.staff ? el('span', { class: 'tagx tagx-open', text: 'Ekip' }) : null, el('span', { text: c.date })),
        el('p', { class: 'comment-text', text: c.body }),
        el('div', { class: 'comment-actions' },
          user && !c.parent_id ? el('button', { class: 'linkbtn', type: 'button', text: 'Yanıtla', onclick: () => form(c.id, c.name) }) : null,
          c.mine ? el('button', { class: 'linkbtn', type: 'button', text: 'Sil', onclick: async () => {
            if (!confirm('Yorumunuz silinsin mi?')) return;
            const r = await api(`/api/comments/${c.id}/delete`, { method: 'POST' });
            if (r.ok) { toast('Yorum silindi.'); load(); } else toast(r.error || 'Silinemedi.', 'error');
          } }) : null),
        replies.length ? el('div', { class: 'replies' }, replies.map((r) => node(r, []))) : null));
    const tops = data.comments.filter((c) => !c.parent_id);
    list.replaceChildren(...tops.map((c) => node(c, data.comments.filter((r) => r.parent_id === c.id))));
  };

  const form = (parentId = 0, replyTo = '') => {
    const area = el('textarea', { id: 'comment-body', maxlength: 2000, required: true, 'aria-describedby': 'comment-hint' });
    const f = el('form', { class: 'comment-form' },
      el('label', { for: 'comment-body', text: replyTo ? `${replyTo} adlı kullanıcıya yanıtınız` : `Yorumunuz (${user.name} olarak)` }),
      area,
      el('div', { class: 'row' },
        el('span', { class: 'hint', id: 'comment-hint', text: 'En fazla 2000 karakter. Yalnızca düz metin; bağlantılar tıklanabilir olmaz.' }),
        el('span', {}, parentId ? el('button', { class: 'btn btn-ghost', type: 'button', text: 'Vazgeç', onclick: () => form() }) : null, ' ',
          el('button', { class: 'btn', type: 'submit', text: 'Gönder' }))));
    f.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = f.querySelector('[type=submit]');
      btn.disabled = true;
      const r = await api('/api/comments', { method: 'POST', body: { post_id: postId, parent_id: parentId, body: area.value } });
      btn.disabled = false;
      if (!r.ok) { toast(r.error || 'Yorum gönderilemedi.', 'error'); return; }
      toast(r.message);
      form();
      if (r.status === 'approved') load();
    });
    slot.replaceChildren(f);
    if (parentId) area.focus();
  };

  if (user) form();
  load();
}

export async function init(mePromise) {
  const article = $('article[data-post-id]');
  const postId = Number(article.dataset.postId);
  progress();
  toc();
  copyAndShare();
  // Count the view once the visitor has actually stayed on the page.
  setTimeout(() => {
    if (document.visibilityState === 'visible') {
      fetch('/api/view', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ post_id: postId }), keepalive: true, credentials: 'same-origin' }).catch(() => {});
    }
  }, 8000);
  const io = new IntersectionObserver(async (entries) => {
    if (!entries.some((e) => e.isIntersecting)) return;
    io.disconnect();
    comments(postId, await mePromise);
  }, { rootMargin: '800px' });
  io.observe($('#comments'));
}
