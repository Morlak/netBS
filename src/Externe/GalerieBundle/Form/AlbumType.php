<?php

namespace Externe\GalerieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Externe\GalerieBundle\Form\DataTransformer\DossierParentTransformer;

class AlbumType extends AbstractType
{
    private $em;
    private $droit;
    
    public function __construct($em, $droit) {
        
        $this->em    = $em;
        $this->droit = $droit;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $transformer = new DossierParentTransformer($this->em);
        
        $builder
            ->add('nom')
            ->add(
                $builder->create('dossier', 'choice', array('choices' => $this->buildFolderListe()))
                        ->addModelTransformer($transformer)
            )
        ;
    }
    
    /**
     * construit la liste des dossiers pour le champ de choix du groupe
     * parent
     */
    private function buildFolderListe() {
        
        $choices = array();
        $types   = $this
            ->em
            ->getRepository('ExterneGalerieBundle:Dossier')
            ->createQueryBuilder('d')
            ->orderBy('d.nom', 'ASC')
            ->where('d.droit = :droit')
            ->setParameter('droit', $this->droit)
            ->getQuery()
            ->getResult();
            
        $choices['0'] = 'Dossier racine';

        foreach ($types as $type) {
            
            $choices[$type->getId()] = $type->getNom();
        }

        return $choices;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Externe\GalerieBundle\Entity\Album'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'externe_galeriebundle_album';
    }
}
