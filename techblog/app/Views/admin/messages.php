<?php /** @var array $rows */ ?>
<?php foreach ($rows as $m): ?>
<article class="panel">
  <h2><?= e($m['subject']) ?> <?= (int)$m['is_read'] ? '' : '<span class="pill pill-warn">yeni</span>' ?></h2>
  <p class="muted"><?= e($m['name']) ?> · <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a> · <?= e(tr_date($m['created_at'], true)) ?></p>
  <p><?= nl2br(e($m['message'])) ?></p>
  <form method="post" action="/admin/messages/<?= (int)$m['id'] ?>/delete" data-confirm="Bu mesaj silinsin mi?"><?= csrf_field() ?><button class="btn btn-danger btn-s" type="submit">Sil</button></form>
</article>
<?php endforeach; ?>
<?php if (!$rows): ?><p class="muted">Henüz iletişim mesajı yok.</p><?php endif; ?>
