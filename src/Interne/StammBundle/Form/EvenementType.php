<?php

namespace Interne\StammBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('categorie', 'choice', array(
            	
            		'choices'	=> array('BS' => 'BS', 'JS' => 'JS', 'ASVD' => 'ASVD', 'MSDS' => 'MSDS', 'other' => 'Autre'),
            		'multiple' 	=> false,
            	))
            ->add('debut', 'datetime', array(
            	
            		'date_widget'    => 'single_text',
            	))
            ->add('fin', 'datetime', array(
            	
            		'date_widget'    => 'single_text',
            	))
        ;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\StammBundle\Entity\Evenement'
        ));
    }

    public function getName()
    {
        return 'interne_stammbundle_evenementtype';
    }
}
