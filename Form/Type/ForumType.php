<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Valantir\ForumBundle\Form\Transformer\EntityToIdTransformer;

class ForumType extends AbstractType {
    
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', null, array(
                'required' => false
            ))
            ->add('description', null, array(
                'required' => false
            ))
            ->add('parent', 'entity_hidden', array(
                'class' => 'Valantir\ForumBundle\Entity\Forum',
                'data' => $this->getParentEntity(),
                'data_class' => null,
                'required' => false
            ))
            ->add('save', 'submit')
        ;
    }

    public function getParentEntity() {
        return $this->container->get('manager.valantir.forum')->find($this->container->get('request')->get('id'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Valantir\ForumBundle\Entity\Forum'
        ));
    }
    
    public function getName() {
        return 'forum_type';
    }
}