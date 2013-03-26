<?php
namespace Sphax\SpriteBundleIHM\Twig\Extension;

use Sphax\SpriteBundle\Entity\SpriteConf;

class GetSpriteConf extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function getFunctions() {
        return array(
            'getDetailConf' => new \Twig_Function_Method($this, 'getDetailConf'),
        );
    }
    
    public function getDetailConf($id, $conf) {
        $em = $this->container->get('doctrine')->getEntityManager();
        $detailSpriteConf = $em->getRepository('SphaxSpriteBundle:SpriteConf')->findOneBy(array('sprite' => $id));
        $value = "get".ucfirst($conf);
        
        return $detailSpriteConf->$value();
    }
        
    public function getName() {
        return 'get_sprite_conf';
    }
}