<?php

return YII_DEBUG?[
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=happy_socker',
    'username' => 'root',
    'password' => '123',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
]:[
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=jinsifang_com',
    'username' => 'jinspang_com',
    'password' => '7zK5KTL6aWzyxweK',
    'charset' => 'utf8',
];
