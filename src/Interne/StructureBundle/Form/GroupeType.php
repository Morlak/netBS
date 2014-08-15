<?php

namespace Interne\StructureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('parent', 'entity', array(
            		
            		'class'		=> 'InterneStructureBundle:Groupe',
            		'property'	=> 'nom'
            	))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\StructureBundle\Entity\Groupe'
        ));
    }

    public function getName()
    {
        return 'interne_structurebundle_groupetype';
    }
}
