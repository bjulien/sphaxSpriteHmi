<?php
namespace Sphax\SpriteHmiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('optipng', 'checkbox',
                array(
                    'required'  => false,
            ))
            ->add('cachebuster', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('less', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('crop', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('quiet', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('retina', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('imagemagick', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('watch', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('html', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('png8', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('ignore_filename_paddings', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('debug', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('cachebuster_filename', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('follow_links', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('no_img', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('no_css', 'checkbox',
                array(
                      'required'  => false,
            ))
            ->add('namespace', 'text', array(
                'attr' => array(
                    'placeholder' => 'my namespace'
                ),
                'required' => false
            ))
            ->add('url', 'url', array(
                'attr' => array(
                    'placeholder' => 'http://static.example.com/'
                ),
                'required' => false
            ))
            ->add('padding', 'integer', array(
                'required' => false
            ))
            ->add('margin', 'integer', array(
                'required' => false
            ))
            ->add('ratios', 'text', array(
                'attr' => array(
                    'placeholder' => '2,1'
                ),
                'required' => false
            ))
            ->add('css', 'text', array(
                'attr' => array(
                    'placeholder' => 'css/dir'
                ),
                'required' => false
            ))
            ->add('img', 'text', array(
                'attr' => array(
                    'placeholder' => 'images/dir'
                ),
                'required' => false
            ))
            ->add('algorithm', 'text', array(
                'attr' => array(
                    'placeholder' => 'square|vertical|hortizontal|diagonal|vertical-right|horizontal-bottom'
                ),
                'required' => false
            ))
            ->add('ordering', 'text', array(
                'attr' => array(
                    'placeholder' => 'maxside|width|height|area'
                ),
                'required' => false
            ))
            ->add('sprite_namespace', 'text', array(
                'attr' => array(
                    'placeholder' => 'my sprite-namespace'
                ),
                'required' => false
            ))
            ->add('imagemagickpath', 'text', array(
                'attr' => array(
                    'placeholder' => 'imagemagick dir'
                ),
                'required' => false
            ))
            ->add('global_template', 'text', array(
                'attr' => array(
                    'placeholder' => "%(all_classes)s{background-image:url('%(sprite_url)s');background-repeat:no-repeat}"
                ),
                'required' => false
            ))
            ->add('each_template', 'text', array(
                'attr' => array(
                    'placeholder' => "%(class_name)s{background-position:%(x)s %(y)s;}"
                ),
                'required' => false
            ))
            ->add('optipngpath', 'text', array(
                'attr' => array(
                    'placeholder' => 'optipngpath dir'
                ),
                'required' => false
            ))
            ->add('separator', 'text', array(
                'attr' => array(
                    'placeholder' => 'camelcase'
                ),
                'required' => false
            ));
    }

    public function getName()
    {
        return 'sphax_spritehmibundle_optionstype';
    }
    
}