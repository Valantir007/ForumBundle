<?php

namespace Valantir\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Valantir\ForumBundle\Form\Transformer\EntityToIdTransformer;

/**
 * Type of form for hidden field with id of entity
 *
 * @author Kamil Demurat
 */
class EntityHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdTransformer($this->objectManager, $options['class']);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('class'))
            ->setDefaults(array(
                'invalid_message' => 'The entity does not exist.'
            ))
        ;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'entity_hidden';
    }
}