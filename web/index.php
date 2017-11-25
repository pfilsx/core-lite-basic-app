<?php

defined('CRL_DEBUG') or define('CRL_DEBUG', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/pfilsx/core-lite/Core.php';

$config = require __DIR__ . '/../config/web.php';

(new \core\base\App($config))->run();