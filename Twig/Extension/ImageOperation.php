<?php
namespace Sphax\SpriteHmiBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\File\File;

class ImageOperation extends \Twig_Extension
{
    protected $kernel;
  
    public function __construct(\AppKernel $kernel)
    {
        $this->kernel = $kernel;
    }
   
    public function getFunctions() {
        return array(
            'getImageSize' => new \Twig_Function_Method($this, 'getImageSize'),
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }
    
    public function getImageSize($image) {

        if (file_exists($image)) {
            list($width, $height, $type, $attr) = getimagesize($image);
        }
        
        $infoFile = array('width' => $width, 'height' => $height, 'size' => filesize($image));

        return $infoFile;
    }
    
    public function image64($path = '') {
        $file = new File($path, false);
        
        if (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/')) {
            return;
        }
        
        $binary = file_get_contents($path);
        
        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }
    
    public function getName() {
        return 'image_operation';
    }
}
