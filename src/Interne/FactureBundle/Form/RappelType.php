<?php

namespace Interne\FactureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RappelType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'numero',
                'number',
                array('label' => 'Numéro')
            )

            ->add(
                'date',
                'date',
                array('label' => 'Date du rappel')
            )
            ->add(
                'frais',
                'number',
                array(  'label' => 'Frais',
                        'required' => false,
                        'data'=> 0
                )
            );


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FactureBundle\Entity\Rappel'
        ));
    }


    public function getName()
    {
        return 'InterneFactureBundle_rappelType';
    }

}
