<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatisticsController extends Controller
{
    public function indexAction()
    {
        return $this->render('InterneFactureBundle:Statistics:panel.html.twig');
    }
}