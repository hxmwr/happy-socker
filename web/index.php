<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$_SERVER['REQUEST_URI'] = str_replace("api/", '', $_SERVER['REQUEST_URI']);
$_SERVER['REQUEST_URI'] = str_replace("admin/", '', $_SERVER['REQUEST_URI']);

$uri = $_SERVER['REQUEST_URI'];

if (preg_match("/^\/(users|bet)(.+)$/", $uri)) {
    // 普通用户
    $config['components']['user']['identityClass'] = 'app\lib\User';
} else {
    // 管理员
    $config['components']['user']['identityClass'] = 'app\models\User';
}

(new yii\web\Application($config))->run();
