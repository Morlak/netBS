<?php

namespace Interne\FactureBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Interne\FactureBundle\Entity\Facture;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Null;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationController extends Controller
{
    public function indexAction()
    {
        return $this->render('InterneFactureBundle:Validation:validation.html.twig');
    }

    public function uploadFileAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $file = $request->files->get('file');

            $array = $this->extractFacturesInFile($file);

            $facturesInFile = $array['factures'];
            $infos = $array['infos'];

            /*
             * On va comparer les facture dans le fichier avec ce qui il y a dans la basse de donnée.
             */
            $results = $this->compareWithFactureInBDD($facturesInFile);

            return $this->render('InterneFactureBundle:Validation:tableLineInput.html.twig',
                array(
                    'results' => $results

                ));




        }


    }

    public function addManualyAjaxAction()
    {

        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $id = $request->request->get('id');
            $montantRecu = $request->request->get('montantRecu');


            $id = (int)$id; //cast sur int

            $receivedFactures = new ArrayCollection();

            $facture = new Facture();
            $facture->setId($id);
            $facture->setMontantRecu($montantRecu);


            $facture->setDatePayement(new \DateTime());

            $receivedFactures[] = $facture;

            /*
             * On va comparer les facture dans le fichier avec ce qui il y a dans la basse de donnée.
             */
            $results = $this->compareWithFactureInBDD($receivedFactures);



            return $this->render('InterneFactureBundle:Validation:tableLineInput.html.twig',
                array(
                    'results' => $results

                ));

        }

        return new Response();
    }

    public function validationAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $id = $request->request->get('id');
            $montantRecu = $request->request->get('montantRecu');
            $datePayement = $request->request->get('datePayement');
            $state = $request->request->get('state');


            $id = (int)$id; //cast sur int

            $em = $this->getDoctrine()->getManager();
            $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);


            $facture->setMontantRecu($montantRecu);
            $facture->setStatut('payee');
            $date = new \DateTime();
            $date->createFromFormat('d/m/Y',$datePayement);
            $facture->setDatePayement($date);



            if($state == 'found_lower_new_facture')
            {
                /*
                 * dans ce cas de figure, on crée une facture supplémentaire
                 * pour compenser le montant exigé
                 */
                $remarque = $facture->getRemarque()
                            .' (Facture crée en complément de la facture numéro: '
                            .$facture->getId()
                            .')';

                $newFacture = new Facture();
                $newFacture->setTitre($facture->getTitre());
                $newFacture->setRemarque($remarque);
                $newFacture->setDateCreation(new \DateTime());
                $newFacture->setMontantEmis($facture->getMontantTotal()-$montantRecu);
                $newFacture->setStatut('ouverte');
                $newFacture->setMontantRecu(0);

                $em->persist($newFacture);
            }

            $em->flush();

            return new JsonResponse($facture->getId());

        }


        return new JsonResponse();


    }

    private function extractFacturesInFile($file)
    {
        /*
         * extraction du contenu du fichier.
         */
        $fileString = file($file);
        $nbLine = count($fileString);

        /*
         * création des conteneurs de résultats de la fonction.
         */
        $facturesInFile = new ArrayCollection();
        $infos = array();

        /*
         * analyse ligne par ligne du fichier-
         */
        for ($i = 0; $i < $nbLine; $i++) {

            $line = $fileString[$i];
            $infos = array();
            $infos['rejetsBvr'] = 0;

            if (substr($line, 0, 1) != 9) {
                //extraction des infos de la ligne
                $numRef = substr($line, 12, 26);
                $montantRecu = substr($line, 39, 10);
                $datePayement = substr($line, 71, 6);
                $rejetBVR = substr($line, 86, 1);

                /*
                 * enregistre le nombre de facture qui ont
                 * été rejetée et rentrée à la main par
                 * la poste.
                 */
                if($rejetBVR)
                {
                    $infos['rejetsBvr'] =$infos['rejetsBvr']+1;
                }

                //reformatage des chaines de caractère
                $numRef = (integer)ltrim($numRef,0);
                $montantRecu = (float)(ltrim($montantRecu,0)/100);
                $date_payement_annee = '20'. substr($datePayement,0,2);
                $date_payement_mois = substr($datePayement,2,2);
                $date_payement_jour = substr($datePayement,4,2);
                $datePayement = new \DateTime();
                $datePayement->setDate((int)$date_payement_annee,(int)$date_payement_mois,(int)$date_payement_jour);

                /*
                 * création de la facture extraite de la ligne
                 */
                $facture = new Facture();
                $facture->setId($numRef);
                $facture->setMontantRecu($montantRecu);
                $facture->setDatePayement($datePayement);

                $facturesInFile[] = $facture;
            }
            else
            {
                /*
                 * Infos sur les factures présente dans ce fichier.
                 * Elle sont stoquées sur la ligne qui commence
                 * par un 9.
                 */
                $infos['genreTransaction'] = substr($line, 0, 3);
                $infos['montantTotal'] = ltrim(substr($line, 39, 12),0);
                $infos['nbTransactions'] = ltrim(substr($line, 51, 12),0);
                $infos['dateDisquette'] = substr($line, 63, 6);
                $infos['taxes'] = substr($line, 69, 9);

            }
        }



        return array('factures' => $facturesInFile, 'infos' => $infos);
    }

    private function compareWithFactureInBDD($facturesInFile)
    {
        $em = $this->getDoctrine()->getManager();
        $factureRepository = $em->getRepository('InterneFactureBundle:Facture');


        $results = array();

        foreach($facturesInFile as $factureInFile)
        {


            $factureFound = $factureRepository->find($factureInFile->getId());

            if($factureFound != Null)
            {
                if($factureFound->getStatut() == 'ouverte')
                {
                    $montantTotalEmis = $factureFound->getMontantTotal();
                    $montantRecu = $factureInFile->getMontantRecu();

                    if($montantTotalEmis == $montantRecu)
                    {
                        $validationStatut = 'Found:Valid';
                    }
                    elseif($montantTotalEmis > $montantRecu)
                    {
                        $validationStatut = 'Found:Lower';
                    }
                    elseif($montantTotalEmis < $montantRecu)
                    {
                        $validationStatut = 'Found:Upper';
                    }
                }
                else
                {
                    /*
                     * la facture a déjà été payée
                     */
                    $validationStatut = 'Found:AlreadyPayed';
                }


            }
            else
            {
                $validationStatut = 'NotFound';
            }

            $results[] = array(
                'factureInFile' => $factureInFile,
                'factureFound' => $factureFound,
                'validationStatut' => $validationStatut
            );



        }



        return $results;
    }
}
