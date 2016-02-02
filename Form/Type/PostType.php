<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for post
 *
 * @author Kamil Demurat
 */
class PostType extends AbstractType
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
        $builder->add('description', null, array(
            'label' => false,
            'required' => false,
            'attr' => array(
                'class' => 'bb-editor form-control post-description', 
                'data-locale' => $this->container->get('request')->getLocale()
            )
        ));

        if ($options['submit']) {
            $builder->add('save', 'submit', array(
                'label' => 'label.save',
                'attr' => array(
                    'class' => 'post-submit btn btn-default'
                )
            ));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'submit' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'post_type';
    }
}