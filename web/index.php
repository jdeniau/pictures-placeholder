<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Jdeniau\PlaceholderPictureBundle\Controller\DefaultController;

$app = new Silex\Application();

// ... definitions

$app->get('/{width}/{height}', function (Silex\Application $app, $width, $height) {
    $controller = new DefaultController;
    return $controller->imageAction($width, $height);
});

$app->get('/g/{width}/{height}', function (Silex\Application $app, $width, $height) {
    $controller = new DefaultController;
    return $controller->grayscaleImageAction($width, $height);
});

$app->run();
