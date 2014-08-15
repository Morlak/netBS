<?php

namespace Interne\StructureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObtentionDistinctionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('obtention', 'text', array(
            	
            		'attr'	=> array('class'	=> 'datepicker')
            	))
            ->add('distinction', 'entity', array(
            	
            		'class'		=> 'InterneStructureBundle:Distinction',
            		'property'	=> 'nom'
            	))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\StructureBundle\Entity\ObtentionDistinction'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interne_structurebundle_obtentiondistinction';
    }
}
