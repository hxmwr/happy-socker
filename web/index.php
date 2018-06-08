<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$uri = $_SERVER['REQUEST_URI'];

if (preg_match("/^\/(users|bet)(.+)$/", $uri)) {
    $config['components']['user']['identityClass'] = 'app\lib\User';
} else {
    $config['components']['user']['identityClass'] = 'app\models\User';
}

(new yii\web\Application($config))->run();
