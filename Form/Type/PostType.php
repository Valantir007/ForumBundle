<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostType extends AbstractType {    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('description', 'clear_post_type', array(
                'label' => false
            ))
            ->add('save', 'submit', array(
                'label' => 'label.save'
            ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Valantir\ForumBundle\Entity\Post',
        ));
    }
    
    public function getName() {
        return 'post_type';
    }
}