<?php

namespace Interne\FichierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

use Interne\FichierBundle\Form\ContactType;
use Interne\FichierBundle\Form\AdresseType;
use Interne\StructureBundle\Form\AttributionType;

class MembreType extends AbstractType
{
	/**
	 * Formulaire pour ajouter un membre, gestion automatique de la détection
	 * de famille
	 */
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('famille', 'entity', array(
        			
        			'class'			=> 'InterneFichierBundle:Famille',
        			'property'		=> 'Nom',
        			'empty_value'	=> 'Choisir une famille',
        		))
            ->add('prenom')
            ->add('naissance', 'text', array(
            		
            		'attr'	=> array('placeholder' => 'format ISO : YYYY-MM-JJ')
            	))
            ->add('sexe', 'choice', array('choices'	=> array('homme'	=> 'Homme', 'femme'	=> 'Femme')))
            ->add('numeroAvs', 'text', array('required' => false))
            ->add('contact', new ContactType)
            ->add('adresse', new AdresseType)
            ->add('groupe', 'entity', array(
            	
            		'class'			=> 'InterneStructureBundle:Groupe',
            		'property'		=> 'nom',
            		'mapped'		=> false,
            		'empty_value'	=> 'Choisir son groupe'
            	))
        ;
        
        $groupeValidator = function(FormEvent $event) {
        	
            $form   = $event->getForm();
            $groupe = $form->get('groupe')->getData();
            if (empty($groupe)) {
              $form['groupe']->addError(new FormError("Choisir un groupe"));
            }
        };

        // adding the validator to the FormBuilderInterface
        $builder->addEventListener(FormEvents::POST_BIND, $groupeValidator);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Interne\FichierBundle\Entity\Membre'
        ));
    }


    public function getName()
    {
        return 'interne_fichierbundle_membretype';
    }
}
