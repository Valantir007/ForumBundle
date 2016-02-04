<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Form for search
 *
 * @author Kamil Demurat
 */
class SearchType extends AbstractType
{
    /**
     * @var Router 
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate('search_result'))
            ->add('phrase', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'search',
                    'class' => 'search form-control',
                    'aria-describedby' => 'search_type_search'
                ),
                'trim' => true
            ))
            ->add('search', 'submit', array(
                'label' => 'label.search',
                'attr' => array(
                    'class' => 'search-submit btn btn-default'
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
            'attr' => array(
                'class' => 'form-inline forum-search-form',
                'action' => 'search_result'
            ),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search_type';
    }
}