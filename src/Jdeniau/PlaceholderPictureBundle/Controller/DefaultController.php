<?php

namespace Jdeniau\PlaceholderPictureBundle\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class DefaultController implements ControllerProviderInterface
{
    /**
     * parameters
     *
     * @var array
     * @access private
     */
    private $parameters;

    /**
     * @InheritedDoc
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $this->parameters = $app['parameters'];

        $controller = $this;
        $controllers->get('/', function (Application $app) use ($controller) {
            return $app['twig']->render('index.html.twig');
        });

        return $controllers;
    }
}
