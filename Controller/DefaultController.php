<?php
namespace Sphax\SpriteHmiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sphax\SpriteHmiBundle\Form\SpriteType;
use Sphax\SpriteHmiBundle\Form\ConfType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * @Route("/sprite")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexSprite")
     * @Template("SphaxSpriteBundle:sprite:listSprite.html.twig")
     */
    public function indexAction()
    {
        $sprite = $this->container->get('sphax.sprite');
        $spriteList = $sprite->getSpriteList();
        //$sprite->createSprite();
        //$sprite->generateSpriteAction();

        $form = $this->createForm(new SpriteType(), $sprite);
        
        return array('listSprite' => $listSprite,
            'form' => $form->createView()
        );
        
        return array('spriteList' => $spriteList,
            'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/{name}", name="detailSprite")
     * @Template("SphaxSpriteBundle:sprite:detailSprite.html.twig")
     */
    public function detailAction($name)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $detailSprite = $em->getRepository('SphaxSpriteBundle:Sprite')->findOneBy(array('id' => $id));     
        $detailSpriteConf = $em->getRepository('SphaxSpriteBundle:SpriteConf')->findOneBy(array('sprite' => $id));     
        
        $dirGlobal = dirname($this->get('kernel')->getRootDir());
        $dirAsset = strtolower(substr($detailSprite->getBundle(), 0,-6));
        $fileSpriteConf = $dirGlobal.'/src/'.$detailSprite->getSite()->getName().'/'.$detailSprite->getBundle().'/Resources/public/images/'.$detailSprite->getDossierImage().'/sprite.conf';
        $exclude_list = array(".", "..", "sprite.conf");
        $spriteGenerate = false;
        if (file_exists($dirGlobal.'/src/'.$detailSprite->getSite()->getName().'/'.$detailSprite->getBundle().'/Resources/public/sprites/'.$detailSprite->getDossierImage().'.png')) {
            $spriteGenerate = true;
        }
        $dossierImageSprite = array_diff(scandir($dirGlobal.'/src/'.$detailSprite->getSite()->getName().'/'.$detailSprite->getBundle().'/Resources/public/images/'.$detailSprite->getDossierImage()), $exclude_list);

        $form = $this->createForm(new ConfType(), $detailSpriteConf);

        return array('detailSprite' => $detailSprite,
                    'imgListSprite' => $dossierImageSprite,
                    'dirAsset' => $dirAsset,
                    'detailSpriteConf' => $detailSpriteConf,
                    'spriteGenerate' => $spriteGenerate,
                    'form' => $form->createView()
        );
    }

    /**
     * @Route("/create", name="createSprite")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     */
    public function createSpriteAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $listSprite = $em->getRepository('SphaxSpriteBundle:Sprite')->findAll();
        $dirGlobal = dirname($this->get('kernel')->getRootDir());

        $sprite = new Sprite();
        $form = $this->createForm(new SpriteType(), $sprite);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {

                //directory of site (ex: Sphax ...)
                $dirSite = $form->get('site')->getData()->getName();
                //directory of Bundle (ex: SpriteBundle ...)
                $dirBundle = $form->get('bundle')->getData();
                //directory of img dir (ex: toto ...)
                $dirDossierImage = $form->get('dossierImage')->getData();
                // sprite name
                $spriteName = $form->get('name')->getData();

                $sprite = $this->container->get('sphax.sprite');
                $sprite->createSprite($listSprite, $dirGlobal, $spriteName, $dirSite, $dirBundle, $dirDossierImage, $sprite);
            }
        }

        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/delete-{name}", name="deleteSprite")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     *
     */
    public function deleteSpriteAction($name)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $spriteInfo = $em->getRepository('SphaxSpriteBundle:Sprite')->findOneBy(array('id' => $id));
        $spriteConf = $em->getRepository('SphaxSpriteBundle:SpriteConf')->findOneBy(array('sprite' => $id));
        
        $dirGlobal = dirname($this->get('kernel')->getRootDir());
        //directory of site (ex: Sphax ...)
        $dirSite = $spriteInfo->getSite()->getName();
        //directory of Bundle (ex: SpriteBundle ...)
        $dirBundle = $spriteInfo->getBundle();
        //directory of img dir (ex: toto ...)
        $dirDossierImage = $spriteInfo->getDossierImage();
        //explode name bundle
        $dirAsset = strtolower(substr($dirBundle, 0,-6));

        $filesystem = new Filesystem();
        //delete dir from src ...
        $filesystem->remove($dirGlobal.'/src/'.$dirSite.'/'.$dirBundle.'/Resources/public/images/'.$dirDossierImage);
        $filesystem->remove($dirGlobal.'/src/'.$dirSite.'/'.$dirBundle.'/Resources/public/sprites/'.$dirDossierImage.'.css');
        $filesystem->remove($dirGlobal.'/src/'.$dirSite.'/'.$dirBundle.'/Resources/public/sprites/'.$dirDossierImage.'.png');

        //delete dir from web ...
        $filesystem->remove($dirGlobal.'/web/bundles/'.strtolower($dirSite).$dirAsset.'/images/'.$dirDossierImage);
        $filesystem->remove($dirGlobal.'/web/bundles/'.strtolower($dirSite).$dirAsset.'/sprites/'.$dirDossierImage.'.png');
        $filesystem->remove($dirGlobal.'/web/bundles/'.strtolower($dirSite).$dirAsset.'/sprites/'.$dirDossierImage.'.css');

        //delete from DB
        $em->remove($spriteInfo);
        $em->remove($spriteConf);
        $em->flush();

        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/deleteImage", name="deleteImage")
     * @Template("SphaxSpriteBundle::layout.html.twig")
     *
     */
    public function deleteImageAction()
    {
        if ($this->getRequest()->getMethod() === 'POST') {
            $dirGlobal = dirname($this->get('kernel')->getRootDir());
            //directory of site (ex: Sphax ...)
            $dirSite = $this->getRequest()->query->get('site');
            //directory of Bundle (ex: SpriteBundle ...)
            $dirBundle = $this->getRequest()->query->get('bundle');
            //directory of img dir (ex: toto ...)
            $dirDossierImage = $this->getRequest()->query->get('dossierImage');
            //explode name bundle
            $dirAsset = strtolower(substr($dirBundle, 0,-6));

            $filesystem = new Filesystem();

            foreach($_POST['deleteImage'] as $img) {
                 $filesystem->remove($dirGlobal.'/src/'.$dirSite.'/'.$dirBundle.'/Resources/public/images/'.$dirDossierImage.'/'.$img);
                 $filesystem->remove($dirGlobal.'/web/bundles/'.strtolower($dirSite).$dirAsset.'/images/'.$dirDossierImage.'/'.$img);
            }
        }

        return $this->redirect($this->generateUrl('indexSprite'));
    }
    
    /**
     * @Route("/add", name="addImage")
     * @Template()
     */
    public function addAction()
    {
        $dirGlobal = dirname($this->get('kernel')->getRootDir());
        $dir = $dirGlobal.'/src/'.$this->getRequest()->query->get('site').'/'.$this->getRequest()->query->get('bundle').'/Resources/public/images/'.$this->getRequest()->query->get('dossierImage');

        $typeAccepted = array("image/jpeg", "image/gif", "image/png");
        $i=0;
        $dirAsset = strtolower(substr($this->getRequest()->query->get('bundle'), 0,-6));
        
        // upload de tous les fichiers
        foreach ($_FILES['imageSprite']['name'] as $value)  {
            if (in_array($_FILES['imageSprite']['type'][$i], $typeAccepted)) {
                $tmp_name = $_FILES['imageSprite']["tmp_name"][$i];
                $name = $value;
                move_uploaded_file($tmp_name, "$dir/$name");
            } else {
                echo "ce fichier n'est pas une image";
            }
            $i++;
        }

        // copie des fichiers images dans le dossier web
        $filesystem = new Filesystem();
        $exclude_list = array(".", "..", "sprite.conf");
        $trueFile = array_diff(scandir($dir), $exclude_list);
        
        foreach ($trueFile as $key => $value) {
            $filesystem->copy($dir.'/'.$value, $dirGlobal.'/web/bundles/'.strtolower($this->getRequest()->query->get('site')).$dirAsset.'/images/'.$this->getRequest()->query->get('dossierImage').'/'.$value, true);
        }

        return $this->redirect($this->generateUrl('indexSprite'));
    }

    /**
     * @Route("/generateSprite", name="generateSprite")
     * @Template()
     */
    public function generateSpriteAction() 
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $spriteInfo = $em->getRepository('SphaxSpriteBundle:Sprite')->findOneBy(array('id' => $this->getRequest()->query->get('id')));
        $spriteOldConf = $em->getRepository('SphaxSpriteBundle:SpriteConf')->findOneBy(array('id' => $this->getRequest()->query->get('idConf')));

        $spriteConf = new SpriteConf();
        $form = $this->createForm(new ConfType(), $spriteConf);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em->remove($spriteOldConf);
                $em->flush();
                $spriteConf->setSprite($spriteInfo);
                $em->persist($spriteConf);
                $em->flush();
            }
        }

        $filesystem = new Filesystem();

        $dirGlobal = dirname($this->get('kernel')->getRootDir());
        $dir = $dirGlobal.'/src/'.$this->getRequest()->query->get('site').'/'.$this->getRequest()->query->get('bundle').'/Resources/public/images/'.$this->getRequest()->query->get('dossierImage');
        $dirAsset = strtolower(substr($this->getRequest()->query->get('bundle'), 0,-6));
        $fileSpriteConf = $dir.'/sprite.conf';

        if (!$handle = fopen($fileSpriteConf, 'w')) {
                    throw new DirectoryException('Cannot open file'.$file, 1);
                }
        fwrite($handle, $spriteConf->getFileConf());
        fclose($handle);

        // génération du sprite
        system('glue '.$dir.' '.$dirGlobal.'/src/'.$this->getRequest()->query->get('site').'/'.$this->getRequest()->query->get('bundle').'/Resources/public/sprites', $retval);

        $filesystem->mirror($dirGlobal.'/src/'.$this->getRequest()->query->get('site').'/'.$this->getRequest()->query->get('bundle').'/Resources/public/sprites', $dirGlobal.'/web/bundles/'.strtolower($this->getRequest()->query->get('site')).$dirAsset.'/sprites');

        return $this->forward('SphaxSpriteBundle:Default:detail', array('id' =>$this->getRequest()->query->get('id')));
    }
}
