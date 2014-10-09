<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MembreType extends AbstractType
{
    /**
     * Formulaire pour ajouter un membre, gestion automatique de la détection de famille
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'numeroBs',
                'number',
                array('label' => 'Numéro BS')
            )

            ->add(
                'prenom',
                'text',
                array('label' => 'Prénom')
            )

            ->add(
                'nom',
                'text',
                array('read_only' => true)
            )

            ->add(
                'naissance',
                'birthday',
                array(
                    'widget' => 'single_text',
                    'label' => 'Date de naissance'
                )
            )

            ->add(
                'sexe',
                new GenreType()
            )

            ->add(
                'numeroAvs',
                'text',
                array(
                    'label' => 'Numéro AVS',
                    'required' => false
                )
            )


//            ->add(
//                'telephones',
//                'collection',
//                array(
//                    'label'         => 'Numéros de téléphone',
//                    'type'          => 'text',
//                    'allow_add'     => true,
//                    'allow_delete'  => true,
//                    'prototype'     => true,
//
//                    'options' => array(
//                        'required' => false
//                    )
//                )
//            )
//
//            ->add(
//                'emails',
//                'collection',
//                array(
//                    'label'         => 'Courriels',
//                    'type'          => 'email',
//                    'allow_add'     => true,
//                    'allow_delete'  => true,
//                    'prototype'     => true,
//
//                    'options'  => array(
//                        'required' => false
//                    )
//                )
//            )

            ->add(
                'remarques',
                'textarea'
            )

            ->add(
                'id',
                'hidden'
            );

//        $groupeValidator = function(FormEvent $event) {
//
//            $form   = $event->getForm();
//            $groupe = $form->get('groupe')->getData();
//            if (empty($groupe)) {
//              $form['groupe']->addError(new FormError("Choisir un groupe"));
//            }
//        };

        // adding the validator to the FormBuilderInterface
//        $builder->addEventListener(FormEvents::POST_BIND, $groupeValidator);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Membre'
        ));
    }


    public function getName()
    {
        return 'InterneFichierBundle_membre';
    }

}
