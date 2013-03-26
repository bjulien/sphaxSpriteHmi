<?php
namespace Sphax\SpriteBundleIHM\Twig\Extension;

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
        );
    }
    
    public function getImageSize($image) {
        list($width, $height, $type, $attr) = getimagesize($this->kernel->getContainer()->get('kernel')->getRootDir().'/../web'.$image);
        $infoFile = array('width' => $width, 'height' => $height, 'size' => filesize($this->kernel->getContainer()->get('kernel')->getRootDir().'/../web'.$image));

        return $infoFile;
    }

        
    public function getName() {
        return 'image_operation';
    }
}