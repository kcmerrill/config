<?php

require_once __DIR__ . '/../src/kcmerrill/utility/config.php';

$app_config = new kcmerrill\utility\config;


$app_config['sys.app_name'] = 'My Application Name!';
$app_config['sys.app_started'] = microtime();

$app_config->c('sys.app_lang', 'en');
$app_config->c('sys.app_timezone', 'Americas/Denver');



echo 'My application name is: ' . $app_config['sys.app_name'] . PHP_EOL . "\n";
echo 'My application name is: ' . $app_config->c('sys.app_name') . PHP_EOL . "\n";