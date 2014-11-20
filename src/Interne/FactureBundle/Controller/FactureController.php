<?php

namespace Interne\FactureBundle\Controller;

use Interne\FactureBundle\Entity\Facture;
use Interne\FactureBundle\Entity\Rappel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Interne\FactureBundle\Form\FactureType;
use Symfony\Component\Validator\Constraints\Null;
use Interne\FactureBundle\Form\FactureSearchType;
use Doctrine\Common\Collections\ArrayCollection;

class FactureController extends Controller
{
    public function testAction()
    {


        $str = 'Test';
        $facture = new Facture();
        $facture->setTitre($str);
        $facture->setRemarque($str);
        $facture->setMontantEmis(0);
        $facture->setMontantRecu(0);
        $facture->setDateCreation(new \DateTime());
        $facture->setStatut('ouvert');

        $rappel1 = new Rappel();
        $rappel1->setDate(new \DateTime());
        $rappel1->setFrais(77);

        $facture->addRappel($rappel1);


        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();


        return $this->render('InterneFactureBundle:Default:index.html.twig');


    }

    public function createAction(Request $request)
    {

        $str = 'Test';
        $facture = new Facture();
        $facture->setTitre($str);
        $facture->setRemarque($str);
        $facture->setMontantEmis(999);
        $facture->setMontantRecu(0);
        $facture->setDateCreation(new \DateTime());
        $facture->setStatut('ouvert');

        $rappel1 = new Rappel();
        $rappel1->setDate(new \DateTime());
        $rappel1->setFrais(77);




        $factureForm  = $this->createForm(new FactureType, $facture);


        //$request = $this->getRequest();
        //$factureForm->handleRequest($request);
        //$factureForm->bindRequest($request);


        if ($request->isMethod('POST'))
        {
            $factureForm->handleRequest($request);

            if ($factureForm->isValid()) {

                //$facture->addRappel($rappel1);



                //$facture = $factureForm->getData();





                return $this->render('InterneFactureBundle:Facture:show.html.twig',
                    array('facture' => $facture));
            }
            else
            {
                /*
                 * affiche les erreurs du formulaire.
                 */
                echo($factureForm->getErrorsAsString());
            }
        }



        return $this->render('InterneFactureBundle:Facture:create.html.twig', array(
            'factureForm' => $factureForm->createView()
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);

        //on verifie que la facture existe bien, si c'est pas le cas, on affiche l'index
        if($facture == Null)
        {
            return $this->render('InterneFactureBundle:Default:index.html.twig');
        }


        return $this->render('InterneFactureBundle:Facture:show.html.twig',
            array('facture' => $facture));

    }

    public function showAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('InterneFactureBundle:Facture')->findAll();

        //on verifie que la facture existe bien, si c'est pas le cas, on affiche l'index
        if($factures == Null)
        {
            return $this->render('InterneFactureBundle:Default:index.html.twig');
        }

        return $this->render('InterneFactureBundle:Facture:liste.html.twig', array('factures' => $factures));

    }

    public function updateAction($id)
    {
        $request = $this->get('request');

        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);

        $factureForm  = $this->createForm(new FactureType, $facture);

        if ($request->isMethod('POST'))
        {
            $factureForm->submit($request);

            if ($factureForm->isValid()) {


                $em->flush();

                return $this->render('InterneFactureBundle:Default:index.html.twig');
            }
        }

        return $this->render('InterneFactureBundle:Facture:update.html.twig', array(
            'factureForm' => $factureForm->createView()
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);

        //on verifie que la facture existe bien, si c'est pas le cas, on affiche l'index
        if($facture != Null)
        {
            $em->remove($facture);
            $em->flush();
        }
        return $this->render('InterneFactureBundle:Default:index.html.twig');
    }

    public function searchAction(Request $request)
    {
        $facture = new Facture();
        $factureSearchForm  = $this->createForm(new FactureSearchType, $facture);

        if ($request->isMethod('POST'))
        {
            $factureSearchForm->submit($request);

            $facture = $factureSearchForm->getData();
            /*
             * On récupère les éléments de recherche non compris dans la facture
             */
            $nombreRappel = $factureSearchForm->get('nombreRappel')->getData();
            $montantEmisMinimum = $factureSearchForm->get('montantEmisMinimum')->getData();
            $montantEmisMaximum = $factureSearchForm->get('montantEmisMaximum')->getData();
            $montantRecuMinimum = $factureSearchForm->get('montantRecuMinimum')->getData();
            $montantRecuMaximum = $factureSearchForm->get('montantRecuMaximum')->getData();
            /*
             * Tableau contenant les paramètres de recherche suplémentaire
             */
            $searchParameters = array(
                    'nombreRappel' => $nombreRappel,
                    'montantEmisMaximum' => $montantEmisMaximum,
                    'montantEmisMinimum' => $montantEmisMinimum,
                    'montantRecuMaximum' => $montantRecuMaximum,
                    'montantRecuMinimum' => $montantRecuMinimum,

            );

            //crée le tableau qui contiendra les réponses
            $factures = new ArrayCollection();

            /*
             * pour la recherche on utilise la fonction personalisée de
             * recheche de facture qui se trouve dans factureRepository.php
             */
            $em = $this->getDoctrine()->getManager();
            $factures = $em->getRepository('InterneFactureBundle:Facture')->findBySearch($facture,$searchParameters);

            return $this->render('InterneFactureBundle:Facture:liste.html.twig', array('factures' => $factures));


        }

        return $this->render('InterneFactureBundle:Facture:search.html.twig', array(
            'factureForm' => $factureSearchForm->createView()
        ));
    }


}