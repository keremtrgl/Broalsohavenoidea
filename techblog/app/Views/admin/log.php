<?php /** @var string $name @var string $tail @var bool $truncated */ ?>
<div class="toolbar"><a class="btn btn-ghost btn-s" href="/admin/system">← Sisteme dön</a><span class="muted mono"><?= e($name) ?><?= $truncated ? ' · yalnızca son 60 kB gösteriliyor' : '' ?></span></div>
<pre class="log" tabindex="0"><?= e($tail !== '' ? $tail : 'Dosya boş.') ?></pre>
