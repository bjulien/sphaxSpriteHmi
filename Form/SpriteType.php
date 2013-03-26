<?php

namespace Sphax\SpriteBundleIHM\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SpriteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
            ->add('name')
            ->add('bundle')
            ->add('dossierImage')
                ->add('site', 'entity', array(
                                        'class' => 'Sphax\SpriteBundle\Entity\Site',
                                              'property' => 'name',
                                              'expanded' => true,
                                              'multiple' => false,
                                              'required' => false
                        ));
    }

    public function getName()
    {
        return 'sphax_spritebundle_spritetype';
    }
        
        public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Sphax\SpriteBundle\Entity\Sprite'
        );
    }
}