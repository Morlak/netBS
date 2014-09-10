<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Interne\FichierBundle\Form\ContactType;
use Interne\FichierBundle\Form\AdresseType;

class GeniteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', 'text', array('required' => false))
            ->add('profession', 'text', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Geniteur'
        ));
    }

    public function getName()
    {
        return 'interne_fichierbundle_geniteurtype';
    }
}
