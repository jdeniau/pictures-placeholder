<?php

namespace Jdeniau\PlaceholderPictureBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jdeniau\PlaceholderPictureBundle\Http\ImagickResponse;

class DefaultController
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * imageAction
     *
     * @param int $width
     * @param int $height
     * @access public
     * @return void
     *
     * @Route("/{width}/{height}")
     */
    public function imageAction($width, $height)
    {
        $path = $this->getPath();

        $imagick = $this->getImage($path, $width, $height);

        return new ImagickResponse($imagick, getimagesize($path)['mime']);
    }

    /**
     * grayscaleImageAction
     *
     * @param int $width
     * @param int $height
     * @access public
     * @return void
     *
     * @Route("/g/{width}/{height}")
     */
    public function grayscaleImageAction($width, $height)
    {
        $path = $this->getPath();

        $imagick = $this->getImage($path, $width, $height);
        $imagick->setImageColorspace(\Imagick::COLORSPACE_GRAY);

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
     * @access private
     * @return string
     */
    private function getPath()
    {
        $dir = '/home/j_deniau/hedgehogs/';

        $files = scandir($dir);

        $candidates = [];
        foreach ($files as $file) {
            if (in_array(pathinfo($dir . $file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $candidates[] = $file;
            }
        }

        return $dir . $candidates[array_rand($candidates)];
    }
}
