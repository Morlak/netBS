<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\FactureBundle\Entity\Creance;
use Interne\FichierBundle\Entity\Membre;
use Symfony\Component\Validator\Constraints\DateTime;
use Interne\FactureBundle\Entity\Facture;

class CreanceController extends Controller
{

    public function waitingListeAction(){

        $em = $this->getDoctrine()->getManager();
        //cherche toute les créance sans factures.
        $creances = $em->getRepository('InterneFactureBundle:Creance')->findByFacture(null);

        return $this->render('InterneFactureBundle:Creance:waitingListe.html.twig',
            array('creances' => $creances));

    }

    public function addToListeAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $listeIds = $request->request->get('listeIds');
            $titre = $request->request->get('titre');
            $remarque = $request->request->get('remarque');
            $montant = $request->request->get('montant');

            foreach($listeIds as $idMembre)
            {
                $creance = new Creance();
                $creance->setDateCreation(new \DateTime());
                $creance->setMontantEmis($montant);
                $creance->setRemarque($remarque);
                $creance->setTitre($titre);

                $em = $this->getDoctrine()->getManager();
                $membre = $em->getRepository('InterneFichierBundle:Membre')->find($idMembre);

                $membre->addCreance($creance);

                $em->persist($creance);
                $em->flush();
            }

            return new Response();

        }
        return new Response();
    }

    public function addAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $idMembre = $request->request->get('idMembre');
            $titre = $request->request->get('titre');
            $remarque = $request->request->get('remarque');
            $montant = $request->request->get('montant');

            $creance = new Creance();
            $creance->setDateCreation(new \DateTime());
            $creance->setMontantEmis($montant);
            $creance->setRemarque($remarque);
            $creance->setTitre($titre);

            $em = $this->getDoctrine()->getManager();
            $membre = $em->getRepository('InterneFichierBundle:Membre')->find($idMembre);

            $membre->addCreance($creance);

            $em->persist($creance);
            $em->flush();

            return $this->render('InterneFactureBundle:viewForFichierBundle:listeForMembre.html.twig',
                array('membre' => $membre));

        }
        return new Response();
    }

    private function createFacture($listeIdCreance)
    {
        /*
         * Remarque: cette fonction va grouper les factures par unité de
         * facturation. Cela marche uniquement pour les factures
         * présente dans la liste d'IDs
         */

        /*
         * On load la base de donnée
         */
        $em = $this->getDoctrine()->getManager();
        $creanceRepo = $em->getRepository('InterneFactureBundle:Creance');

        /*
         * On va mettre les créance de la liste dans des facture
         */

        foreach($listeIdCreance as $creanceId)
        {
            $creance = $creanceRepo->find($creanceId);
            /*
             * La fonction va parcourire la liste des creances mais il se peut que
             * la facturation aie été déjà faite dans une itération précédente.
             * On va donc s'assurer que la créance n'est pas encore liée à une
             * facture.
             */
            if($creance->getFacture() == null)
            {
                /*
                 * On commence par regarder si la créance
                 * appartien à un membre ou une famille.
                 * Ainsi que déterminer la cible de facturation
                 */
                $famille = $creance->getFamille();
                $membre = $creance->getMembre();

                $cibleFacturation = '';

                if($famille != null)
                {
                    /*
                     * la créance appartien à une famille
                     */
                    $cibleFacturation = 'Famille';
                }
                elseif($membre!= null)
                {
                    /*
                     * la cérance appartient à un membre
                     */
                    $cibleFacturation = $membre->getEnvoiFacture(); //retourne soit 'Famille' soit 'Membre'
                    if($cibleFacturation == 'Famille')
                    {
                        //on récupère la famille du membre
                        $famille = $membre->getFamille();
                    }
                }

                /*
                 * Creation de la nouvelle facture
                 */
                $facture = new Facture();
                $facture->setMontantRecu(0);
                $facture->setDateCreation(new \DateTime());
                $facture->setStatut('ouverte');


                /*
                 * On procède de manière différente selon
                 *  la cible de facturation.
                 */

                switch($cibleFacturation){

                    case 'Membre':

                        foreach($membre->getCreances() as $linkedCreance)
                        {
                            /*
                             * On récupère toute les créances du membre
                             * qui ne sont pas encore facturée
                             * !!! Et qui apparitennent à la liste !!!
                             */
                            if((!$linkedCreance->isFactured()) && in_array($linkedCreance->getId(),$listeIdCreance))
                            {
                                $facture->addCreance($linkedCreance);
                            }
                        }
                        $membre->addFacture($facture);

                        break;

                    case 'Famille':

                        foreach($famille->getCreances() as $linkedCreance)
                        {
                            /*
                             * On récupère toute les créances de la famille
                             * qui ne sont pas encore facturée
                             * !!! Et qui apparitennent à la liste !!!
                             */
                            if((!$linkedCreance->isFactured()) && in_array($linkedCreance->getId(),$listeIdCreance))
                            {
                                $facture->addCreance($linkedCreance);
                            }
                        }

                        foreach($famille->getMembres() as $membreOfFamille)
                        {
                            /*
                             * On recherche des créances chez les
                             * membre de la famille qui envoie
                             * leurs facture à la famille
                             */
                            if($membreOfFamille->getEnvoiFacture() == 'Famille')
                            {
                                foreach($membreOfFamille->getCreances() as $linkedCreance)
                                {
                                    /*
                                     * On récupère toute les créances du membre
                                     * qui ne sont pas encore facturée
                                     * !!! Et qui apparitennent à la liste !!!
                                     */
                                    if((!$linkedCreance->isFactured())&& in_array($linkedCreance->getId(),$listeIdCreance))
                                    {
                                        $facture->addCreance($linkedCreance);
                                    }
                                }
                            }
                        }

                        $famille->addFacture($facture);
                        break;

                }

                $em->persist($facture);
                $em->flush();
            }
        }
    }

    public function facturationAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            /*
             * On récupère les données
             */
            $fromPage = $request->request->get('fromPage');
            $listeIdCreance = $request->request->get('listeCreance');

            //cération des nouvelles factures
            $this->createFacture($listeIdCreance);


            /*
             * On load la base de donnée
             */
            $em = $this->getDoctrine()->getManager();
            $creanceRepo = $em->getRepository('InterneFactureBundle:Creance');

            /*
             * On charge juste la premier créance
             * c'est suffisant pour retrouver la
             * famille ou le membre.
             */
            $creance = $creanceRepo->find($listeIdCreance[0]);


            //adaptation du rendu selon la provenance
            if ($fromPage == 'WaitingList') {
                return new Response();
            } elseif ($fromPage == 'Membre') {

                return $this->render('InterneFactureBundle:viewForFichierBundle:listeForMembre.html.twig',
                    array('membre' => $creance->getMembre()));


            } elseif ($fromPage == 'Famille') {

            }

        }
        return new Response();
    }



}
