<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostType extends AbstractType {
        
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('description', null, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'bb-editor',
                    'data-locale' => $this->container->get('request')->getLocale()
                )
            ));
        
        if($options['submit']) {
            $builder->add('save', 'submit', array(
                'label' => 'label.save'
            ));
        }
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'submit' => true
        ));
    }
    
    public function getName() {
        return 'post_type';
    }
}