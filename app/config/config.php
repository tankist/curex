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
    'cache' => [
        'metadata' => [
            'className' => '\Phalcon\Mvc\Model\MetaData\Apc',
            'options' => [
                'prefix' => 'meta-data',
                'lifetime' => 86400,
            ],
        ],
        'models' => [
            'frontend' => [
                'className' => '\Phalcon\Cache\Frontend\Data',
                'options' => [
                    'lifetime' => 7200,
                ],
            ],
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Memcache',
                'options' => [
                    'servers' => [
                        [
                            'host' => '127.0.0.1',
                            'port' => '11211',
                        ]
                    ]
                ],
            ],
        ],
        'view' => [
            'frontend' => [
                'className' => '\Phalcon\Cache\Frontend\Output',
                'options' => [
                    'lifetime' => 7200,
                ],
            ],
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Memcache',
                'options' => [
                    'servers' => [
                        [
                            'host' => '127.0.0.1',
                            'port' => '11211',
                        ]
                    ]
                ],
            ],
        ],
    ],
    'acl' => [
        'roles' => [
            'user' => 'Authorized users',
            'guest' => 'non-Authorized visitors',
            'admin' => 'God',
        ],
        'resources' => [
            'offers' => 'Main offers area',
            'oauth' => 'oAuth authorization area',
        ],
        'rights' => [
            'deny' => [
                ['guest', '*', '*'],
            ],
            'allow' => [
                ['guest', 'oauth', '*'],
                ['user', '*', '*'],
            ],
        ],
    ],
], (array)$local));
