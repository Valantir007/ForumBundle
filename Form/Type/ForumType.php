<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Valantir\ForumBundle\Entity\Forum;

/**
 * Form for forum
 *
 * @author Kamil Demurat
 */
class ForumType extends AbstractType
{
    /**
     * @var ContainerInterface 
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('parent', 'entity_hidden', array(
                'class' => 'Valantir\ForumBundle\Entity\Forum',
                'data' => $this->getParentEntity(),
                'data_class' => null,
                'required' => false
            ))
            ->add('save', 'submit', array(
                'label' => 'label.save'
            ))
        ;
    }

    /**
     * @return Forum|null
     */
    public function getParentEntity()
    {
        return $this->container->get('manager.valantir.forum')->findOneBy(array('slug' => ($this->container->get('request')->get('slug'))));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Valantir\ForumBundle\Entity\Forum'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'forum_type';
    }
}