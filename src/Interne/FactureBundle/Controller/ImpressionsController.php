<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\FactureBundle\Entity\Rappel;
use Interne\FactureBundle\Entity\Facture;

class ImpressionsController extends Controller
{
    public function indexAction()
    {
        return $this->render('InterneFactureBundle:Default:index.html.twig');
    }




}