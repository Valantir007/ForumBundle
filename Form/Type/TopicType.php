<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Valantir\ForumBundle\Entity\Post;

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
                    'placeholder' => 'placeholder.name',
                    'class' => 'topic-name form-control'
                )
            ))
            ->add('description', null, array(
                'required' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'placeholder.description',
                    'class' => 'topic-description form-control'
                )
            ))
            ->add('posts', 'collection', array(
                'entry_type' => 'post_type',
                'allow_add' => false,
                'label' => false,
                'attr' => array(
                    'class' => 'post-description',
                ),
                'options' => array(
                    'label' => false,
                    'data_class' => 'Valantir\ForumBundle\Entity\Post',
                    'submit' => false
                ),
                'empty_data' => array(new Post())
            ))
            ->add('save', 'submit', array(
                'label' => 'label.save',
                'attr' => array(
                    'class' => 'topic-submit btn btn-default'
                )
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