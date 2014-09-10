<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rue',        'text',         array('required' => false))
            ->add('npa',        'number',       array('required' => false))
            ->add('localite',   'text',         array('required' => false))
            ->add('facturable', 'checkbox',     array('required' => false))
            ->add('remarques',  'textarea',     array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Adresse'
        ));
    }

    public function getName()
    {
        return 'interne_fichierbundle_adressetype';
    }
}
