<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TopicType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', null, array(
                'required' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'placeholder.name'
                )
            ))
            ->add('description', null, array(
                'required' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'placeholder.description'
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'label.save'
            ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Valantir\ForumBundle\Entity\Topic',
        ));
    }
    
    public function getName() {
        return 'topic_type';
    }
}