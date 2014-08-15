<?php

namespace Externe\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DroitType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupe', 'entity', array(
                'class'       => 'InterneStructureBundle:Groupe',
                'property'    => 'nom'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Externe\GalerieBundle\Entity\Droit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'externe_galeriebundle_droit';
    }
}
