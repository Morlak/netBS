<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MembreContactType extends AbstractType
{
	/**
	 * Formulaire pour ajouter un membre, gestion automatique de la détection
	 * de famille
	 */
	 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add(
                'telephones',
                'collection',
                array(
                    'label'         => 'Numéros de téléphone',
                    'type'          => 'text',
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'prototype'     => true,

                    'options' => array(
                        'required' => false
                    )
                )
            )

            ->add(
                'emails',
                'collection',
                array(
                    'label'         => 'Courriels',
                    'type'          => 'email',
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'prototype'     => true,

                    'options'  => array(
                        'required' => false
                    )
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
        return 'interne_fichierbundle_membrecontacttype';
    }

    public function getLabel()
    {
        return "Informations de contact";
    }
}
