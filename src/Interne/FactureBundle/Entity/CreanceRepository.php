<?php

namespace Interne\FactureBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CreanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CreanceRepository extends EntityRepository
{
    /*
     * cette fonction est utilisée par le formulaire de recherche de facture.
     * on crée une requete custom.
     */
    public function findBySearch($creance,$searchParameters = null)
    {
        //on crée un nouvelle requete qui sera custom
        $queryBuilder = $this->createQueryBuilder('creance');

        /*
         * Elements de recherche contenu dans le formulaire de creance standard
         */
        $parameter = $creance->getTitre();
        if($parameter != null)
        {
            /*
             * Recherche tout les titre où le bout de texte $parametre est présent
             */
            $queryBuilder->andWhere($queryBuilder->expr()->like('creance.titre',$queryBuilder->expr()->literal('%'.$parameter.'%')) );
        }

        $parameter = $creance->getRemarque();
        if($parameter != null)
        {
            $queryBuilder->andWhere($queryBuilder->expr()->like('creance.remarque',$queryBuilder->expr()->literal('%'.$parameter.'%')) );

        }

        $parameter = $creance->getMontantEmis();
        if($parameter != null)
        {
            $queryBuilder->andWhere('creance.montantEmis = :montantEmis')->setParameter('montantEmis', $parameter);
        }

        $parameter = $creance->getMontantRecu();
        if($parameter != null)
        {
            $queryBuilder->andWhere('creance.montantRecu = :montantRecu')->setParameter('montantRecu', $parameter);
        }

        $parameter = $creance->getDateCreation();
        if($parameter != null)
        {
            $queryBuilder->andWhere('creance.dateCreation = :dateCreation')
                ->setParameter('dateCreation', $parameter);
        }

        /*
         *
         * Elements de recherche spécifique qui permet d'affiner la recherche.
         *
         */

        if($searchParameters != null)
        {

            $parameter = $searchParameters['creance']['isLinkedToFacture'];
            if ($parameter != null) {
                if($parameter == 'yes') //donc la créance est liée
                {
                    $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('creance.facture'));

                }
                else //donc la cérance n'a pas encore de facture
                {
                    $queryBuilder->andWhere($queryBuilder->expr()->isNull('creance.facture'));

                }
            }

            /*
             * Intervale pour les montants
             */

            $parameter = $searchParameters['creance']['montantEmisMinimum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.montantEmis >= :montantEmisMinimum')
                    ->setParameter('montantEmisMinimum', $parameter);
            }

            $parameter = $searchParameters['creance']['montantEmisMaximum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.montantEmis <= :montantEmisMaximum')
                    ->setParameter('montantEmisMaximum', $parameter);
            }

            $parameter = $searchParameters['creance']['montantRecuMinimum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.montantRecu >= :montantRecuMinimum')
                    ->setParameter('montantRecuMinimum', $parameter);
            }

            $parameter = $searchParameters['creance']['montantRecuMaximum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.montantRecu <= :montantRecuMaximum')
                    ->setParameter('montantRecuMaximum', $parameter);
            }

            /*
             * Intervale date de création
             */

            $parameter = $searchParameters['creance']['dateCreationMaximum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.dateCreation <= :dateCreationMaximum')
                    ->setParameter('dateCreationMaximum', $parameter);
            }
            $parameter = $searchParameters['creance']['dateCreationMinimum'];
            if ($parameter != null) {
                $queryBuilder->andWhere('creance.dateCreation >= :dateCreationMinimum')
                    ->setParameter('dateCreationMinimum', $parameter);
            }


            /*
             * relation avec les membres et famille
             */
            if(($searchParameters['creance']['membreNom'] != null) || ($searchParameters['creance']['membrePrenom'] != null) || ($searchParameters['creance']['familleNom'] != null))
            {

                if(($searchParameters['creance']['membreNom'] != null) || ($searchParameters['creance']['membrePrenom'] != null))
                {
                    //On lie avec le membre
                    $queryBuilder->innerJoin('Interne\FichierBundle\Entity\Membre', 'membre', 'WITH', 'membre.id = creance.membre');

                    $parameter = $searchParameters['creance']['membrePrenom'];
                    if ($parameter != null) {
                        $queryBuilder->andWhere($queryBuilder->expr()->like('membre.prenom',$queryBuilder->expr()->literal('%'.$parameter.'%')) );
                    }

                    $parameter = $searchParameters['creance']['membreNom'];
                    if ($parameter != null) {

                        //On lie avec la famille
                        $queryBuilder->innerJoin('Interne\FichierBundle\Entity\Famille', 'famille', 'WITH', 'membre.id = membre.famille');

                        $queryBuilder->andWhere($queryBuilder->expr()->like('famille.nom',$queryBuilder->expr()->literal('%'.$parameter.'%')) );
                    }
                }
                
                /*
                 * lien avec la famille
                 */

                if($searchParameters['creance']['familleNom'] != null)
                {
                    //On lie avec la famille
                    $queryBuilder->innerJoin('Interne\FichierBundle\Entity\Famille', 'famille', 'WITH', 'famille.id = creance.famille');

                    $parameter = $searchParameters['creance']['familleNom'];
                    if ($parameter != null) {
                        $queryBuilder->andWhere($queryBuilder->expr()->like('famille.nom',$queryBuilder->expr()->literal('%'.$parameter.'%')) );
                    }
                }
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
