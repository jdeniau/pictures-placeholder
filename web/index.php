<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Jdeniau\PlaceholderPictureBundle\Controller\DefaultController;

$app = new Silex\Application();
$app['debug'] = true;

// register providers
$app->register(
    new Silex\Provider\TwigServiceProvider,
    [
        'twig.path' => __DIR__ . '/../app/views',
    ]
);
$app->register(
    new Igorw\Silex\ConfigServiceProvider(
        __DIR__ . '/../app/config/parameters.yml',
        array('root_dir' => __DIR__ . '/..')
    )
);
$app->register(
    new Igorw\Silex\ConfigServiceProvider(
        __DIR__ . '/../app/config/copyrights.json'
    )
);

$app->mount('/', new DefaultController);
$app->run();
