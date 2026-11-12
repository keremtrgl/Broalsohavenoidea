<?php /** @var array $errors @var array $old @var bool $sent */ ?>
<div class="wrap">
  <header class="page-head">
    <span class="kicker">İletişim</span>
    <h1>Bize yazın</h1>
    <p>Düzeltme, öneri ve iş birliği talepleriniz için formu kullanabilirsiniz.</p>
  </header>
  <?php if ($sent): ?><p class="notice" role="status">Mesajınız alındı. En kısa sürede yanıtlayacağız.</p><?php endif; ?>
  <?php if (!empty($errors['form'])): ?><p class="form-err" role="alert"><?= e($errors['form']) ?></p><?php endif; ?>
  <form class="form" method="post" action="/contact" novalidate>
    <?= csrf_field() ?>
    <div class="hp" aria-hidden="true"><label>Bu alanı boş bırakın <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <?php
    $fields = [['name', 'Adınız', 'text', 'name', 120], ['email', 'E-posta adresiniz', 'email', 'email', 190], ['subject', 'Konu', 'text', 'off', 200]];
    foreach ($fields as [$key, $label, $type, $ac, $max]): ?>
    <div class="field">
      <label for="f-<?= $key ?>"><?= e($label) ?></label>
      <input type="<?= $type ?>" id="f-<?= $key ?>" name="<?= $key ?>" value="<?= e($old[$key] ?? '') ?>" maxlength="<?= $max ?>" autocomplete="<?= $ac ?>" required<?= isset($errors[$key]) ? ' aria-invalid="true" aria-describedby="e-' . $key . '"' : '' ?>>
      <?php if (isset($errors[$key])): ?><span class="err" id="e-<?= $key ?>"><?= e($errors[$key]) ?></span><?php endif; ?>
    </div>
    <?php endforeach; ?>
    <div class="field">
      <label for="f-message">Mesajınız</label>
      <textarea id="f-message" name="message" maxlength="5000" required<?= isset($errors['message']) ? ' aria-invalid="true" aria-describedby="e-message"' : '' ?>><?= e($old['message'] ?? '') ?></textarea>
      <?php if (isset($errors['message'])): ?><span class="err" id="e-message"><?= e($errors['message']) ?></span><?php endif; ?>
    </div>
    <p class="hint">Gönderdiğiniz bilgiler yalnızca size yanıt vermek için kullanılır. Ayrıntılar: <a href="/privacy">Gizlilik politikası</a>.</p>
    <div><button class="btn" type="submit">Mesajı gönder</button></div>
  </form>
</div>
