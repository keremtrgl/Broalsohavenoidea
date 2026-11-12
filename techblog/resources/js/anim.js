// SVG animation engine. Elements declare an animation in data-a; everything runs through the
// Web Animations API using only transform and opacity. Animations start when the figure enters
// the viewport, pause when it leaves, and never run under prefers-reduced-motion.
import { $$, el, reducedMotion } from './util.js';

const EASE = 'cubic-bezier(.45,.05,.3,1)';
const num = (v, d) => (v === undefined || v === '' || Number.isNaN(Number(v)) ? d : Number(v));
const clamp = (v) => Math.min(1, Math.max(0, v));

/** Build keyframes for one element. Times t0/t1 are fractions of the shared cycle. */
function build(spec, cycle) {
  const [type, ...a] = spec.trim().split(/\s+/);
  const loop = { duration: cycle * 1000, iterations: Infinity, fill: 'both' };
  const fade = 0.025;
  switch (type) {
    case 'move': {
      const dx = num(a[0], 0), dy = num(a[1], 0), t0 = clamp(num(a[2], 0)), t1 = clamp(num(a[3], 1)), mode = a[4] || '';
      const end = `translate(${dx}px,${dy}px)`;
      const before = mode === 'show' ? 1 : 0;
      const after = mode ? 1 : 0;
      return [[
        { offset: 0, transform: 'translate(0,0)', opacity: before },
        { offset: t0, transform: 'translate(0,0)', opacity: before },
        { offset: clamp(t0 + fade), transform: 'translate(0,0)', opacity: 1, easing: EASE },
        { offset: clamp(Math.max(t1 - fade, t0 + fade)), transform: end, opacity: 1 },
        { offset: t1, transform: end, opacity: after },
        { offset: 1, transform: end, opacity: after },
      ], loop];
    }
    case 'route': {
      const nums = a.filter((x) => !x.includes(','));
      const pts = [[0, 0], ...a.filter((x) => x.includes(',')).map((p) => p.split(',').map(Number))];
      const t0 = clamp(num(nums[0], 0)), t1 = clamp(num(nums[1], 1));
      const frames = [{ offset: 0, transform: 'translate(0,0)', opacity: 0 }, { offset: t0, transform: 'translate(0,0)', opacity: 0 }];
      pts.forEach(([x, y], i) => {
        const o = t0 + ((t1 - t0) * i) / (pts.length - 1);
        frames.push({ offset: clamp(i === 0 ? o + fade : o - (i === pts.length - 1 ? fade : 0)), transform: `translate(${x}px,${y}px)`, opacity: 1 });
      });
      const [lx, ly] = pts[pts.length - 1];
      frames.push({ offset: t1, transform: `translate(${lx}px,${ly}px)`, opacity: 0 }, { offset: 1, transform: `translate(${lx}px,${ly}px)`, opacity: 0 });
      return [frames, loop];
    }
    case 'show': {
      const t0 = clamp(num(a[0], 0)), t1 = clamp(num(a[1], 1));
      const frames = [{ offset: 0, opacity: 0 }, { offset: t0, opacity: 0 }, { offset: clamp(t0 + fade), opacity: 1 }];
      if (t1 < 1) frames.push({ offset: clamp(Math.max(t1 - fade, t0 + fade)), opacity: 1 }, { offset: t1, opacity: 0 }, { offset: 1, opacity: 0 });
      else frames.push({ offset: 0.985, opacity: 1 }, { offset: 1, opacity: 0 });
      return [frames, loop];
    }
    case 'grow': {
      const axis = a[0] === 'y' ? 'Y' : 'X', t0 = clamp(num(a[1], 0)), t1 = clamp(num(a[2], 0.5));
      return [[
        { offset: 0, transform: `scale${axis}(0)`, opacity: 0 },
        { offset: t0, transform: `scale${axis}(0)`, opacity: 1, easing: EASE },
        { offset: t1, transform: `scale${axis}(1)`, opacity: 1 },
        { offset: 0.985, transform: `scale${axis}(1)`, opacity: 1 },
        { offset: 1, transform: `scale${axis}(1)`, opacity: 0 },
      ], loop];
    }
    case 'pulse': {
      const s = num(a[0], 1.15), sec = num(a[1], 1.6);
      return [[{ transform: 'scale(1)' }, { transform: `scale(${s})` }, { transform: 'scale(1)' }], { duration: sec * 1000, iterations: Infinity, easing: 'ease-in-out' }];
    }
    case 'wave': {
      const sec = num(a[0], 2.4), delay = num(a[1], 0);
      return [[{ transform: 'scale(.25)', opacity: 0.9 }, { transform: 'scale(1)', opacity: 0 }], { duration: sec * 1000, delay: delay * 1000, iterations: Infinity, easing: 'ease-out', fill: 'backwards' }];
    }
    case 'blink': {
      const sec = num(a[0], 1.2);
      return [[{ opacity: 1 }, { opacity: 0.2 }, { opacity: 1 }], { duration: sec * 1000, iterations: Infinity }];
    }
    case 'spin': {
      const sec = num(a[0], 6);
      return [[{ transform: 'rotate(0deg)' }, { transform: `rotate(${sec < 0 ? -360 : 360}deg)` }], { duration: Math.abs(sec) * 1000, iterations: Infinity }];
    }
    case 'float': {
      const px = num(a[0], 6), sec = num(a[1], 4);
      return [[{ transform: 'translateY(0)' }, { transform: `translateY(${-px}px)` }, { transform: 'translateY(0)' }], { duration: sec * 1000, iterations: Infinity, easing: 'ease-in-out' }];
    }
    default:
      return null;
  }
}

export function init() {
  if (reducedMotion() || !('animate' in Element.prototype)) return; // static illustration stays as drawn
  const figures = $$('.fx').filter((f) => f.querySelector('[data-a]'));
  const state = new WeakMap();

  const start = (fig) => {
    const cycle = num(fig.dataset.dur || fig.querySelector('svg')?.dataset.dur, 6);
    const anims = [];
    $$('[data-a]', fig).forEach((node) => {
      const built = build(node.dataset.a, cycle);
      if (built) anims.push(node.animate(built[0], built[1]));
    });
    const st = { anims, paused: false };
    state.set(fig, st);
    // WCAG 2.2.2: moving content that lasts longer than five seconds needs a pause control.
    const btn = el('button', { type: 'button', text: 'Animasyonu duraklat', 'aria-pressed': 'false' });
    btn.addEventListener('click', () => {
      st.paused = !st.paused;
      st.anims.forEach((an) => (st.paused ? an.pause() : an.play()));
      btn.textContent = st.paused ? 'Animasyonu oynat' : 'Animasyonu duraklat';
      btn.setAttribute('aria-pressed', String(st.paused));
    });
    fig.append(el('div', { class: 'fx-ctl' }, btn));
  };

  const io = new IntersectionObserver((entries) => {
    for (const e of entries) {
      const st = state.get(e.target);
      if (e.isIntersecting && !st) start(e.target);
      else if (st && !st.paused) st.anims.forEach((an) => (e.isIntersecting ? an.play() : an.pause()));
    }
  }, { threshold: 0.15 });
  figures.forEach((f) => io.observe(f));
}
