<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => true, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'sgt-api',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],


        'swagger' => [
            'baseDir' => __DIR__ . '/../src', 
            //  'ignoreDir' => [],
            'routes' => [
                'json' => '/docs/json',
                'view' => '/',
                'resources' => '/api/vendor/junioalmeida/slim-framework-swagger-json-and-viewer/src/resources/{resource}',
            ],
            'projects' => [
                ['name'=>'SGT', 'url'=>'http://200.160.111.85:9090/api/public/docs/json'],
            ],
        ]
    ],
];
