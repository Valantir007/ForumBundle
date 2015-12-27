<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for topic
 *
 * @author Kamil Demurat
 */
class TopicType extends AbstractType
{
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
            ->add('posts', 'collection', array(
                'type' => 'post_type',
                'allow_add' => false,
                'label' => false,
                'options' => array(
                    'label' => false,
                    'data_class' => 'Valantir\ForumBundle\Entity\Post',
                    'submit' => false
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'label.save'
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Valantir\ForumBundle\Entity\Topic',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'topic_type';
    }
}