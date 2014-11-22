<?php

namespace Interne\FactureBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Interne\FactureBundle\Entity\Facture;
use Symfony\Component\Validator\Constraints\Null;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationController extends Controller
{
    public function indexAction()
    {
        return $this->render('InterneFactureBundle:Validation:validation.html.twig');
    }

    public function uploadAction()
    {
        $request = $this->get('request');

        $bvrFileForm = $this->createFormBuilder()
            ->add('bvrFile', 'file',array('label'=>'Fichier BVR (*.V11)'))
            ->getForm();
        $bvrFileForm->handleRequest($request);


        if ($request->isMethod('POST'))
        {

            $bvrFile = $bvrFileForm->get('bvrFile')->getData();
            /*
             * ici on pourrait s'amuser a sauver le fichier quelque part dans le serveur.
             * mais on s'en fou pour l'instant!
             */

            /*
             * On crée un tableau de factures a partire des données du fichier
             */
            $facturesInFile = $this->extractFacturesInFile($bvrFile);


            /*
             * On va comparer les facture dans le fichier avec ce qui il y a dans la basse de donnée.
             */
            $results = $this->compareWithFactureInBDD($facturesInFile);

            return $this->render('InterneFactureBundle:Bvr:validation.html.twig',
                array(
                    'results' => $results

                ));

        }

        return $this->render('InterneFactureBundle:Bvr:upload.html.twig', array(
            'bvrFileForm' => $bvrFileForm->createView()
        ));



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
            $state = $request->request->get('state');

            $id = (int)$id; //cast sur int

            $em = $this->getDoctrine()->getManager();
            $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);


            $facture->setMontantRecu($montantRecu);
            $facture->setStatut('payee');



            if($state == 'found_lower_new_facture')
            {
                /*
                 * dans ce cas de figure, on crée une facture supplémentaire
                 * pour compenser le montant exigé
                 */
                $newFacture = new Facture();
                $newFacture->setTitre($facture->getTitre());
                $newFacture->setRemarque($facture->getRemarque() + '(Texte d explication)');
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

    /**
     *
     *
     * @param File $bvrFile
     * @return ArrayCollection
     */
    private function extractFacturesInFile($bvrFile)
    {
        $bvrFileString = file_get_contents($bvrFile);

        /*
         * extraction a faire ultérieurement
         */

        $facturesInFile = new ArrayCollection();

        $facture1 = new Facture();
        $facture1->setId(19);

        $facture1->setMontantRecu(10);

        $facturesInFile[] = $facture1;


        $facture2 = new Facture();
        $facture2->setId(500);


        $facturesInFile[] = $facture2;

        $facture3 = new Facture();
        $facture3->setId(2);
        $facture3->setMontantRecu(500);


        $facturesInFile[] = $facture3;

        return $facturesInFile;

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
