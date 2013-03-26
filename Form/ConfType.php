<?php
namespace Sphax\SpriteBundleIHM\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('optipng', 'choice',
                array('choices' => array(1 => 'On', 0 =>'Off'),
                    'attr' => array('class' => 'radio'),
                    'expanded' => true,
            ))
            ->add('cachebuster','choice',
                array('choices' => array(1 => 'On', 0 =>'Off'),
                    'attr' => array('class' => 'radio'),
                    'expanded' => true,
            ))
            ->add('less','choice',
                array('choices' => array(1 => 'On', 0 =>'Off'),
                    'attr' => array('class' => 'radio'),
                    'expanded' => true,
            ))
            ->add('namespace', 'text', array('required' => false))
            ->add('separatorConf', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'sphax_spritebundle_conftype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Sphax\SpriteBundle\Entity\SpriteConf'
        );
    }
}