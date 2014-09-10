<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MembreFamilleType extends AbstractType
{
	/**
	 * Formulaire pour ajouter un membre, gestion automatique de la détection
	 * de famille
	 */
	 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        	->add(
                'famille',
                new ChosenType(),
                array(
                    'class'	        => 'InterneFichierBundle:Famille',
                    'empty_value'	=> 'Choisir une famille',
                )
            )

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Membre'
        ));
    }


    public function getName()
    {
        return 'interne_fichierbundle_membrefamilletype';
    }

    public function getLabel()
    {
        return "Informations sur sa famille";
    }
}
