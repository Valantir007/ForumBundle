<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('description', null, array(
                'required' => false,
                'attr' => array(
                    'class' => 'bb-editor'
                )
            ))
            ->add('save', 'submit')
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