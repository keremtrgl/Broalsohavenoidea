<?php
declare(strict_types=1);

/**
 * Front controller. The application lives one level above the web root by default.
 * If your host cannot keep it there, create public_html/tb-path.php that returns the private folder path
 * (see README, "Alternative layout").
 */
define('TB_PUBLIC', __DIR__);
define('TB_BASE', is_file(__DIR__ . '/tb-path.php') ? rtrim((string)require __DIR__ . '/tb-path.php', '/') : dirname(__DIR__));

require TB_BASE . '/app/bootstrap.php';

App\Core\App::run();
