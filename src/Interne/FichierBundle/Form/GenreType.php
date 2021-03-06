<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class GenreType
 *
 * Type à utiliser pour un champ définissant le genre dans un formulaire
 *
 * @package Interne\FichierBundle\Form
 */
class GenreType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'm' => 'Homme',
                'f' => 'Femme',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'genre';
    }
}