<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\FactureBundle\Entity\Rappel;
use Interne\FactureBundle\Entity\Facture;
use Interne\FactureBundle\Entity\Creance;
use Interne\FactureBundle\Form\FactureSearchType;

class SearchController extends Controller
{


    public function searchAction()
    {
        $facture = new Facture();
        $factureSearchForm  = $this->createForm(new FactureSearchType, $facture);

        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $factureSearchForm->submit($request);

            $facture = $factureSearchForm->getData();

            /*
             * On récupère les éléments de recherche non compris dans la facture.
             * Tableau contenant les paramètres de recherche suplémentaire
             */
            $searchParameters = array(
                'nombreRappel' => $factureSearchForm->get('nombreRappel')->getData(),
                'montantEmisMaximum' => $factureSearchForm->get('montantEmisMaximum')->getData(),
                'montantEmisMinimum' => $factureSearchForm->get('montantEmisMinimum')->getData(),
                'montantRecuMaximum' => $factureSearchForm->get('montantRecuMaximum')->getData(),
                'montantRecuMinimum' => $factureSearchForm->get('montantRecuMinimum')->getData(),

            );

            /*
             * pour la recherche on utilise la fonction personalisée de
             * recheche de facture qui se trouve dans factureRepository.php
             */
            $em = $this->getDoctrine()->getManager();
            $factures = $em->getRepository('InterneFactureBundle:Facture')->findBySearch($facture,$searchParameters);

            return $this->render('InterneFactureBundle:Search:results.html.twig', array(

                'factures' => $factures));

        }


        return $this->render('InterneFactureBundle:Search:search.html.twig', array(
        'form' => $factureSearchForm->createView(),
        'factures' => null));
    }




}