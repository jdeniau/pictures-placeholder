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
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $this->parameters = $app['parameters'];

        $controller = $this;
        $controllers->get('/', function (Application $app) use ($controller) {
            $params = $this->parameters;

            return $app['twig']->render(
                'index.html.twig',
                $params
            );
        });

        return $controllers;
    }
}
