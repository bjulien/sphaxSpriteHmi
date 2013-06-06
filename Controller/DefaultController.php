<?php
namespace Sphax\SpriteHmiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sphax\SpriteHmiBundle\Form\ConfType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Sphax\SpriteBundle\Exception\SpriteException;

/**
 * @Route("/sprite")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexSprite")
     * @Template("SphaxSpriteHmiBundle:sprite:listSprite.html.twig")
     */
    public function indexAction()
    {
        $sprite = $this->container->get('sphax.sprite');
        $spriteList = $sprite->getSpriteList();

        $form = $this->createForm(new ConfType());
        
        return array('spriteList' => $spriteList,
            'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/detail/{spriteName}", name="detailSprite")
     * @Template("SphaxSpriteHmiBundle:sprite:detailSprite.html.twig")
     */
    public function detailAction($spriteName)
    {
        $sprite = $this->container->get('sphax.sprite');
        $spriteList = $sprite->getSpriteList();
        foreach ($spriteList as $key => $value) {
            if ($key == $spriteName) {
                $detailInfo = $value;
                $detailInfo['name'] = $key;
            }
        }

        $exclude_list = array(".", "..");
        $DirImage = scandir($detailInfo['sourceSpriteImage']);
        $spriteDirImage = array_diff($DirImage, $exclude_list);

        try {
            $output = scandir($detailInfo['outputSpriteImage']);
        } catch (\Exception $e) {
            throw new \Exception("Output Directory doesn't exists");
        }
        
        if (!empty($detailInfo['outputSpriteImage'])) {
            $dirOutput = array_diff($output, $exclude_list);
        }

        return array(
            'detailInfo' => $detailInfo,
            'spriteDirImage' => $spriteDirImage,
            'dirOutput' => $dirOutput
        );
    }

    /**
     * @Route("/create", name="createSprite")
     */
    public function createSpriteAction(Request $request)
    {
        
        $form = $this->createForm(new ConfType());
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $sprite = $form->getData();
            }
        }

        $yamlParser = $this->container->get('yaml.parser');
        $yamlParser->createSprite($this->get('kernel')->getRootDir().'/../app/config/sprites.yml', $sprite);
        
        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/delete/{spriteName}", name="deleteSprite")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     *
     */
    public function deleteSpriteAction($spriteName)
    {

        $yamlParser = $this->container->get('yaml.parser');
        $yamlParser->deleteSprite($this->get('kernel')->getRootDir().'/../app/config/sprites.yml', $spriteName);
        
        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/modifSprite/{spriteName}", name="modifSprite")
     * @Template("SphaxSpriteHmiBundle:sprite:updateSprite.html.twig")
     *
     */
    public function modifSpriteAction($spriteName)
    {
        $form = $this->createForm(new ConfType());
        $detailInfo = null;
        $sprite = $this->container->get('sphax.sprite');
        $spriteList = $sprite->getSpriteList();
        foreach ($spriteList as $key => $value) {
            if ($key == $spriteName) {
                $detailInfo = $value;
                $detailInfo['name'] = $key;
            }
        }
        
        return array(
            'detailInfo' => $detailInfo,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/updateSprite", name="updateSprite")
     *
     */
    public function updateSpriteAction(Request $request)
    {
        $form = $this->createForm(new ConfType());
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $sprite = $form->getData();
            }
        }

        $yamlParser = $this->container->get('yaml.parser');
        $yamlParser->updateSprite($this->get('kernel')->getRootDir().'/../app/config/sprites.yml', $sprite);
        
        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/generateSprite/{spriteName}", name="generateSprite")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     *
     */
    public function generateSpriteAction($spriteName)
    {
        $sprite = $this->container->get('sphax.sprite');

        try {
            $retval = $sprite->generateOneSprite($spriteName);
            $this->get('session')->getFlashBag()->add('noticeSprite', $retval);
        } catch (SpriteException $e) {
            $this->get('session')->getFlashBag()->add('noticeSprite', 'Error in sprite generation. Make sure you have right on this folder');
        }
        return $this->redirect($this->generateUrl('detailSprite', array('spriteName' => $spriteName)));
    }

    /**
     * @Route("/delImage/{spriteName}-{name}", name="delImage")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     *
     */
    public function delImageAction($spriteName, $name)
    {
        $fs = new Filesystem();

        $sprite = $this->container->get('sphax.sprite');
        $spriteList = $sprite->getSpriteList();
        foreach ($spriteList as $key => $value) {
            if ($key == $spriteName) {
                $detailInfo = $value;
            }
        }

        if ($fs->exists($detailInfo['sourceSpriteImage'].'/'.$name)) {
            $fs->remove($detailInfo['sourceSpriteImage'].'/'.$name);
        } else {
            throw new \Exception("Image doesn't exists");
        }

        return $this->redirect($this->generateUrl('detailSprite', array('spriteName' => $spriteName)));
    }
    
    /**
     * @Route("/add", name="addImage")
     */
    public function addAction(Request $request)
    {
        $upload = $this->container->get('upload');
        $upload->upload($request->request->get('dirImage'), $request->files->get('imageUpload'));

        return $this->redirect($this->generateUrl('detailSprite', array('spriteName' => $request->request->get('spriteName'))));
    }

}
