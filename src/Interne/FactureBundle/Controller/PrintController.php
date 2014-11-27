<?php

namespace Interne\FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Interne\FactureBundle\Entity\Rappel;
use Interne\FactureBundle\Entity\Facture;
use Interne\GlobalBundle\Services\Pdf;

class PrintController extends Controller
{
    public function printAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {
            $id = $request->request->get('id');

            $em = $this->getDoctrine()->getManager();
            $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);


            $pdf = $this->factureToPdf($facture);


            $fileName = 'Facture_' . $facture->getId() . '.pdf';


            return new Response($pdf->Output($fileName,'D'));
        }

        return new Response();
    }


    public function printAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $facture = $em->getRepository('InterneFactureBundle:Facture')->find($id);


            $pdf = $this->factureToPdf($facture);


            $fileName = 'Facture_' . $facture->getId() . '.pdf';

            $pdf->Output($fileName,'D');

        return new Response();
    }


    private function factureToPdf(Facture $facture)
    {
        $pdf = new Pdf();

        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        $fontFamiliy = 'Arial';
        $fontStyle  = '';
        $fontSize = 9;
        $pdf->SetFont($fontFamiliy,$fontStyle,$fontSize);

        $cellWidth = 50;//ne sert pas vraiment
        $cellHigh = 4;



        $adresse = array('Brigade de Sauvabelin','Case Postale 5455','1002 Lausanne');


        /*
         * Adresse haut de page
         */
        $x =  20;
        $y =  20;
        $pdf = $this->insertAdresse($pdf,$x,$y,$cellWidth,$cellHigh,$adresse);

        /*
         * Date
         */
        $x = 130;
        $y =  20;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,'Lausanne, le ');

        /*
         * Adress du membre
         */
        $x =  110;
        $y =  50;
        $adresseMembre = array('Nom Prénom','chemin','Ville');
        $pdf = $this->insertAdresse($pdf,$x,$y,$cellWidth,$cellHigh,$adresseMembre);


        /*
         * Titre de la facture
         */
        $fontFamiliy = 'Arial';
        $fontStyle  = 'B';
        $fontSize = 9;
        $pdf->SetFont($fontFamiliy,$fontStyle,$fontSize);

        $x = 20;
        $y =  70;
        $pdf->SetXY($x,$y);
        $title = 'Facture N°'.$facture->getId().' : '.$facture->getTitre();
        $pdf->Cell($cellWidth,$cellHigh,$title);


        /*
         * Remarque
         *
        $fontFamiliy = 'Arial';
        $fontStyle  = 'B';
        $fontSize = 9;
        $pdf->SetFont($fontFamiliy,$fontStyle,$fontSize);

        $x = 20;
        $y =  90;
        $pdf->SetXY($x,$y);
        $remarques = 'Remarques:'.$facture->getRemarque();
        $pdf->Cell(150,30,'',1);
        $pdf->Cell($cellWidth,$cellHigh,'Remarques:');
        $pdf->SetXY($x,$y+5);
        $pdf->Cell($cellWidth,$cellHigh,$remarques);

        */


        $ccp = '01-66840-7';
        $numeroReference = (string)$facture->getId();


        $pdf = $this->insertBvr($pdf,$adresse,$ccp,$numeroReference);

        $pdf->Output();
        //$pdf->getPdf()->Output('test.pdf','D');

        return $pdf;

    }

    /*
     * Ajoute une adresse au PDF
     */
    private  function insertAdresse($pdf,$xStart,$yStart,$cellWidth,$cellHigh,$arrayTextLine)
    {
        $x = $xStart;
        $y = $yStart;
        foreach($arrayTextLine as $textLine)
        {
            $pdf->SetXY($x,$y);
            $pdf->Cell($cellWidth,$cellHigh,$textLine);
            $y = $y + $cellHigh;
        }
        return $pdf;
    }

    /*
     * Crée les ligne de Codage BVR
     */
    private  function creatLineCode($numeroReference,$type = 'numRef',$ccp = '')
    {
        $numeroReferance = (string)$numeroReference;

        $codeLine = '';
        switch($type)
        {
            case 'numRef':
                $codeLine = '00 00000 00000 00000 00000 00000';
                $codeLineLenght = strlen($codeLine);
                $numeroReferanceLenght = strlen($numeroReferance);
                $spaceIndex = 0;
                for($i = 1; $i <= $numeroReferanceLenght; $i++)
                {
                    $num = substr($numeroReferance,$numeroReferanceLenght-$i,1);
                    $codeChar = substr($codeLine,$codeLineLenght-$spaceIndex-$i-1,1);
                    if($codeChar != '0')
                    {
                        $spaceIndex++;
                    }
                    $codeLine = substr_replace($codeLine,$num,$codeLineLenght-$spaceIndex-$i-1,1);
                }
                break;

            case 'code':
                $codeLine = '042>000000000000000000000000000+ 00000000>';
                $inputCcp = str_replace ('-','',$ccp);
                $codeLineLenght = strlen($codeLine);
                $inputCcpLenght = strlen($inputCcp);
                $codeLine = substr_replace($codeLine,$inputCcp,$codeLineLenght-$inputCcpLenght-1,$inputCcpLenght);
                $numeroReferanceLenght = strlen($numeroReferance);
                $codeLine = substr_replace($codeLine,$numeroReferance,$codeLineLenght-$numeroReferanceLenght-12,$numeroReferanceLenght);
                break;
        }
        return $codeLine;

    }

    /*
     * Ajouter un BVR
     */
    private function insertBvr($pdf,$adresse,$ccp,$numeroReference)
    {

        /*
         * BVR Start Point (X = 0mm ,Y = 190mm) depuis le haut gauche de la page
         *
         *   o ------->X
         *   |
         *   |
         *   |
         *   v
         *   Y
         */

        $xStart = 0;
        $yStart = 190;
        /*
         * ligne de controle
         */
        $pdf->Line($xStart,$yStart,$xStart+5,$yStart);
        $pdf->Line($xStart+60,$yStart,$xStart+60,$yStart+5);
        $pdf->Line($xStart+205,$yStart,$xStart+210,$yStart);
        $pdf->Line($xStart+118,$yStart+80,$xStart+124,$yStart+80);
        $pdf->Line($xStart+121,$yStart+75,$xStart+121,$yStart+80);

        $fontFamiliy = 'Arial';
        $fontStyle  = '';
        $fontSize = 9;

        $cellWidth = 50;//ne sert pas vraiment
        $cellHigh = 4;

        $pdf->SetFont($fontFamiliy,$fontStyle,$fontSize);


        /*
         * Adresse récépissé
         */
        $x = $xStart + 5;
        $y = $yStart + 10;
        $pdf = $this->insertAdresse($pdf,$x,$y,$cellWidth,$cellHigh,$adresse);

        /*
         * compte récépissé
         */
        $x = $xStart + 28;
        $y = $yStart+42;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,$ccp);

        /*
         * num. référence récépissé
         */
        $codeLine = $this->creatLineCode($numeroReference,'numRef');
        $x = $xStart + 5;
        $y = $yStart+60;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,$codeLine);

        /*
         * Adresse virement
         */
        $x = $xStart + 65;
        $y = $yStart + 10;
        $pdf = $this->insertAdresse($pdf,$x,$y,$cellWidth,$cellHigh,$adresse);

        /*
         * compte virement
         */
        $x = $xStart + 89;
        $y = $yStart+42;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,$ccp);

        /*
         * num. référance virement
         */
        $codeLine = $this->creatLineCode($numeroReference,'numRef');
        $x = $xStart + 130;
        $y = $yStart+38;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,$codeLine);

        /*
         * code BVR en bas de coupon
         */
        $pdf->SetFont('Arial', '', 11);

        $codeLine = $this->creatLineCode($numeroReference,'code',$ccp);
        $x = $xStart+68;
        $y = $yStart+85;
        $pdf->SetXY($x,$y);
        $pdf->Cell($cellWidth,$cellHigh,$codeLine);

        return $pdf;

    }

}