<?php

namespace Interne\StammBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DownloadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('file')
            ->add('categorie', 'choice', array(
            	
            	'choices'	=> array('BS' => 'BS', 'JS' => 'JS', 'ASVD' => 'ASVD', 'MSDS' => 'MSDS', 'other' => 'Autre'),
            	'multiple' 	=> false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\StammBundle\Entity\Download'
        ));
    }

    public function getName()
    {
        return 'interne_stammbundle_downloadtype';
    }
}
