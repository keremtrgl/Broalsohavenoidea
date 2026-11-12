// Sign-in page: Firebase Authentication in the browser, then the ID token is exchanged for a PHP session.
// The server verifies the token; nothing here is trusted.
import { initializeApp } from 'firebase/app';
import {
  getAuth, GoogleAuthProvider, signInWithPopup, signInWithEmailAndPassword, createUserWithEmailAndPassword,
  sendEmailVerification, sendPasswordResetEmail, updateProfile, signOut,
} from 'firebase/auth';

const $ = (s) => document.querySelector(s);
const card = $('#auth');
const cfg = JSON.parse($('#firebase-cfg').textContent);
const auth = getAuth(initializeApp(cfg));
auth.languageCode = 'tr';

const errBox = $('#auth-err'), msgBox = $('#auth-msg');
const show = (box, text) => { errBox.hidden = msgBox.hidden = true; if (text) { box.textContent = text; box.hidden = false; } };

const MESSAGES = {
  'auth/invalid-credential': 'E-posta veya parola hatalı.',
  'auth/invalid-email': 'Geçerli bir e-posta adresi yazın.',
  'auth/user-disabled': 'Bu hesap devre dışı bırakılmış.',
  'auth/email-already-in-use': 'Bu e-posta adresiyle zaten bir hesap var. Giriş yapmayı deneyin.',
  'auth/weak-password': 'Parola çok zayıf. En az 8 karakter kullanın.',
  'auth/too-many-requests': 'Çok fazla deneme yapıldı. Bir süre sonra tekrar deneyin.',
  'auth/popup-closed-by-user': 'Giriş penceresi kapatıldı.',
  'auth/popup-blocked': 'Tarayıcınız giriş penceresini engelledi. Açılır pencerelere izin verin.',
  'auth/network-request-failed': 'Ağ hatası. Bağlantınızı kontrol edin.',
  'auth/unauthorized-domain': 'Bu alan adı Firebase konsolunda yetkilendirilmemiş (Authentication → Settings → Authorized domains).',
  'auth/operation-not-allowed': 'Bu giriş yöntemi Firebase konsolunda etkinleştirilmemiş.',
};
const explain = (e) => MESSAGES[e?.code] || 'Giriş yapılamadı. Lütfen tekrar deneyin.';

async function exchange(user) {
  const idToken = await user.getIdToken(true);
  const res = await fetch('/auth/session', {
    method: 'POST',
    credentials: 'same-origin',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-Token': card.dataset.csrf },
    body: JSON.stringify({ idToken, next: card.dataset.next }),
  });
  const data = await res.json().catch(() => ({ ok: false }));
  // The PHP session is the source of truth from here on; the Firebase client session is not needed.
  await signOut(auth).catch(() => {});
  if (!data.ok) { show(errBox, data.error || 'Oturum açılamadı.'); return; }
  location.assign(data.redirect || '/');
}

let mode = 'in';
function setMode(next) {
  mode = next;
  $('#tab-in').setAttribute('aria-selected', String(mode === 'in'));
  $('#tab-up').setAttribute('aria-selected', String(mode === 'up'));
  $('#auth-form').setAttribute('aria-labelledby', mode === 'in' ? 'tab-in' : 'tab-up');
  $('#row-name').hidden = mode === 'in';
  $('#pass-hint').hidden = mode === 'in';
  $('#btn-reset').hidden = mode === 'up';
  $('#btn-email').textContent = mode === 'in' ? 'Giriş yap' : 'Hesap oluştur';
  $('#a-pass').autocomplete = mode === 'in' ? 'current-password' : 'new-password';
  show(errBox, '');
}
$('#tab-in').addEventListener('click', () => setMode('in'));
$('#tab-up').addEventListener('click', () => setMode('up'));

$('#btn-google').addEventListener('click', async () => {
  show(errBox, '');
  try {
    const provider = new GoogleAuthProvider();
    provider.setCustomParameters({ prompt: 'select_account' });
    const cred = await signInWithPopup(auth, provider);
    await exchange(cred.user);
  } catch (e) { show(errBox, explain(e)); }
});

$('#auth-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  show(errBox, '');
  const email = $('#a-email').value.trim(), pass = $('#a-pass').value, name = $('#a-name').value.trim();
  if (!email || pass.length < 8) { show(errBox, 'E-posta adresinizi ve en az 8 karakterlik parolanızı yazın.'); return; }
  const btn = $('#btn-email');
  btn.disabled = true;
  try {
    let cred;
    if (mode === 'up') {
      cred = await createUserWithEmailAndPassword(auth, email, pass);
      if (name) await updateProfile(cred.user, { displayName: name });
      await sendEmailVerification(cred.user).catch(() => {});
    } else {
      cred = await signInWithEmailAndPassword(auth, email, pass);
    }
    await exchange(cred.user);
  } catch (err) { show(errBox, explain(err)); }
  btn.disabled = false;
});

$('#btn-reset').addEventListener('click', async () => {
  const email = $('#a-email').value.trim();
  if (!email) { show(errBox, 'Önce e-posta adresinizi yazın.'); $('#a-email').focus(); return; }
  try {
    await sendPasswordResetEmail(auth, email);
  } catch (e) {
    if (e?.code === 'auth/invalid-email' || e?.code === 'auth/too-many-requests' || e?.code === 'auth/network-request-failed') { show(errBox, explain(e)); return; }
  }
  // Same message whether or not the account exists, so the form cannot be used to probe e-mail addresses.
  show(msgBox, 'Bu adresle bir hesap varsa parola sıfırlama bağlantısı gönderildi.');
});
