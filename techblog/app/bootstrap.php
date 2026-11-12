<?php
declare(strict_types=1);

/**
 * Application bootstrap. Expects TB_BASE (private application root) and TB_PUBLIC (web root) to be defined.
 */

use App\Core\Env;
use App\Core\ErrorHandler;

require TB_BASE . '/vendor/autoload.php';

Env::load(TB_BASE . '/.env');
date_default_timezone_set((string)env('APP_TIMEZONE', 'Europe/Istanbul'));
mb_internal_encoding('UTF-8');
ErrorHandler::register();
