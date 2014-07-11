<?php

namespace Jdeniau\PlaceholderPictureBundle\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Jdeniau\PlaceholderPictureBundle\Http\ImagickResponse;

class PictureController implements ControllerProviderInterface
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

        $controllers->get('/{width}/{height}', function (Application $app, $width, $height) use ($controller) {
            return $controller->imageAction($width, $height);
        });

        $controllers->get('/g/{width}/{height}', function (Application $app, $width, $height) use ($controller) {
            return $controller->grayscaleImageAction($width, $height);
        });

        $controllers->get('/{nb}/g/{width}/{height}', function (Application $app, $width, $height, $nb) use ($controller) {
            return $controller->grayscaleImageAction($width, $height, $nb);
        });

        $controllers->get('/{nb}/{width}/{height}', function (Application $app, $width, $height, $nb) use ($controller) {
            return $controller->imageAction($width, $height, $nb);
        });

        return $controllers;
    }

    /**
     * imageAction
     *
     * @param int $width
     * @param int $height
     * @param int|null $nb
     * @access public
     * @return void
     */
    public function imageAction($width, $height, $nb = null)
    {
        $writePath = $this->getFinalPath($width, $height, $nb);
        if (file_exists($writePath)) {
            return new BinaryFileResponse($writePath);
        }

        $path = $this->getPath($nb);

        $imagick = $this->getImage($path, $width, $height);
        $imagick->writeImage($writePath);

        return new ImagickResponse($imagick, getimagesize($path)['mime']);
    }

    /**
     * grayscaleImageAction
     *
     * @param int $width
     * @param int $height
     * @access public
     * @return void
     */
    public function grayscaleImageAction($width, $height, $nb = null)
    {
        $writePath = $this->getFinalPath($width, $height, $nb, true);
        if (file_exists($writePath)) {
            return new BinaryFileResponse($writePath);
        }

        $path = $this->getPath($nb);

        $imagick = $this->getImage($path, $width, $height);
        //$imagick->setImageColorspace(\Imagick::COLORSPACE_GRAY);
        $imagick->modulateimage(100, 0, 100);
        $imagick->writeImage($writePath);

        return new ImagickResponse($imagick, getimagesize($path)['mime']);
    }


    /**
     * getImage
     *
     * @param string $path
     * @param int $with
     * @param int $height
     * @access private
     * @return \Imagick
     */
    private function getImage($path, $width, $height)
    {
        $crop = new \stojg\crop\CropEntropy($path);
        $imagick = $crop->resizeAndCrop($width, $height);

        return $imagick;
    }

    /**
     * getPath
     *
     * $param int|null $nb
     *
     * @access private
     * @return string
     */
    private function getPath($nb = null)
    {
        $dir = $this->parameters['picture_dir'];

        $files = scandir($dir);

        $candidates = [];
        foreach ($files as $file) {
            if (in_array(pathinfo($dir . $file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $candidates[] = $file;
            }
        }

        $nb = $nb ?: array_rand($candidates);
        return $dir . $candidates[$nb];
    }

    /**
     * writeImage
     *
     * @param \Imagick $imagick
     * @access private
     * @return void
     */
    private function writeImage(\Imagick $imagick)
    {
        $image->writeImage();
    }

    /**
     * getFinalPath
     *
     * @param int $width
     * @param int $height
     * @param boolean $grayscale
     * @param int $nb
     * @access private
     * @return string
     */
    private function getFinalPath($width, $height, $nb = null, $grayscale = false)
    {
        $dir = $this->parameters['picture_cache_dir'];

        $filename = date('Ymd.');
        if ($nb) {
            $filename .= $nb . '.';
        }
        $filename .= $width . '-' . $height;

        if  ($grayscale) {
            $filename .= '.bw';
        }

        return $dir . $filename;
    }
}
