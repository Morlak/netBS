<?php

namespace Interne\StructureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut', 'text', array(
            		
            		'attr'		=> array('placeholder' => 'format ISO : YYYY-MM-JJ', 'class'=>'datepicker')
            	))
            ->add('dateFin', 'text', array(
					
					'required'	=> false,
            		'attr'		=> array('placeholder' => 'format ISO : YYYY-MM-JJ', 'class'=>'datepicker'),
            	))
            ->add('groupe', 'entity', array(
            		
            		'class'		=> 'InterneStructureBundle:Groupe',
            		'property'	=> 'nom'
            	))
            ->add('fonction', 'entity', array(
            	
            		'class'		=> 'InterneStructureBundle:Fonction',
            		'property'	=> 'nom'
            	))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\StructureBundle\Entity\Attribution'
        ));
    }

    public function getName()
    {
        return 'interne_structurebundle_attributiontype';
    }
}
