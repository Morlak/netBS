<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Interne\FichierBundle\Entity\Geniteur;
use Interne\FichierBundle\Entity\Adresse;

class FamilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('required'	=> false))
            ->add('pere', new GeniteurType, array('required' => false))
            ->add('mere', new GeniteurType, array('required' => false))
            ->add('adresse', new AdresseType)
            ->add('contact', new ContactType)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Famille'
        ));
    }

    public function getName()
    {
        return 'interne_fichierbundle_familletype';
    }
}
