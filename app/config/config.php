<?php

$local = @include 'config.local.php';

return new \Phalcon\Config(array_replace_recursive([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'curex',
        'password' => '',
        'dbname' => 'curex',
    ],
    'application' => [
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'baseUri' => 'http://curex.com/',
    ],
], (array)$local));
