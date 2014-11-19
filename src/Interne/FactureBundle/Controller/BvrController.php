<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class BvrController extends Controller
{
    public function uploadAction()
    {
        $request = $this->get('request');

        $bvrFileForm = $this->createFormBuilder()
            ->add('bvrFile', 'file',array('label'=>'Fichier BVR (*.V11)'))
            ->getForm();
        $bvrFileForm->handleRequest($request);


        if ($request->isMethod('POST'))
        {
            //$bvrFileForm->submit($request);
            $bvrFile = $bvrFileForm->get('bvrFile')->getData();
            /*
             * ici on pourrait s'amuser a sauver le fichier quelque part dans le serveur.
             * mais on s'en fou pour l'instant!
             */

            $test = file_get_contents($bvrFile);



            $strArray = explode(' ', $test);

            return $this->render('InterneFactureBundle:Bvr:validation.html.twig',
                array(
                    'test' => $test,
                    'strArray' => $strArray,
                ));

        }

        return $this->render('InterneFactureBundle:Bvr:upload.html.twig', array(
            'bvrFileForm' => $bvrFileForm->createView()
        ));

    }
}
