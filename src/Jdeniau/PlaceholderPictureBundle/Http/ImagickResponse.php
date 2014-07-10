<?php

namespace Jdeniau\PlaceholderPictureBundle\Http;

use \Imagick;
use Symfony\Component\HttpFoundation\Response;

class ImagickResponse extends Response
{

    /**
     * __construct
     *
     * @param Imagick $image
     * @param int $status
     * @param array $headers
     * @access public
     * @return void
     */
    public function __construct(Imagick $image, $contentType, $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);

        $this->headers->set('Content-type', $contentType);
        $this->setContent($image->getImageBlob());
    }
}
