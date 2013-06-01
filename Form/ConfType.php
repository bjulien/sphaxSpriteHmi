<?php
namespace Sphax\SpriteHmiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('nameBin', 'text', array('required' => false))
            ->add('sourceSpriteImage')
            ->add('outputSpriteImage')
            ->add('options', new OptionsType(), 
                array('attr' => array('class' =>'options'),
                      'label_attr' => array('class' => 'label_options'),
                      'label' => 'Options (optional)'
                )
            );
    }

    public function getName()
    {
        return 'sphax_spritebundle_conftype';
    }
}