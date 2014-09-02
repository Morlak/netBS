<?php

namespace Interne\FichierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

//Entity
use Interne\FichierBundle\Entity\Membre;

class ListingController extends Controller
{
    /**
     * le listing est la fonctionnalité qui permet de gérer les listes de membres
     * à travers le netBS, les exportations etc.
     */
    
    /**
     * la méthode retrieveListe va récupérer la liste des membres qui lui est passée
     * en POST (une string d'ids), en JSON
     */
    public function retrieveListeAction() {
        
        $listing = $this->get('request')->request->get('listing');
        
        //Une fois la liste des ids récupérée, on chope les infos pour
        //chaque membre
        $liste = explode(',', $listing);
        $infos = array();

        
        //Variables de base
        $em = $this->getDoctrine()->getManager();
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        
        
        for($i = 0; $i < count($liste); $i++) {
            
            //On stocke les données
            $membre = $mRepo->find($liste[$i]);
            $attr   = $mRepo->findCurrentAttribution($membre->getId());
            
            $infos[$i] = array(
                
                'id'        => $membre->getId(),
                'prenom'    => $membre->getPrenom(),
                'nom'       => $membre->getFamille()->getNom(),
                'fonction'  => ($attr != null) ? $attr->getFonction()->getNom() : '',
                'groupe'    => ($attr != null) ? $attr->getGroupe()->getNom() : '',
                'numeroBS'  => $membre->getNumeroBS()
            );
            
        }

        return new JsonResponse($infos);
    }
    
    /**
     * méthodes d'exportation
     * ======================
     *
     * Exportation PDF, se base sur la classe FPDF
     * http://www.fpdf.org/
     */
    public function exportPDFAction($ids) {
        
        //Récupération des variables
        $em = $this->getDoctrine()->getManager();
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        $ids = explode(',', $ids);
        
        //On récupère FPDF
        $pdf = new \Interne\FichierBundle\Ext\PDFTemplate();
        
        //On construit le document
        $pdf->FPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',10);
        
        /**
         * on génère ensuite le tableau liste
         * en premier lieu, on crée le header du tableau avec les titres
         */
        $pdf->Cell(30,9,utf8_decode('Prénom'),'B',0,'L');
        $pdf->Cell(30,9,'Nom','B',0,'L');
        $pdf->Cell(50,9,'Rue','B',0,'L');
        $pdf->Cell(16,9,'NPA','B',0,'L');
        $pdf->Cell(32,9,utf8_decode('Localité'),'B',0,'L');
        $pdf->Cell(32,9,utf8_decode('Téléphone'),'B',0,'L');
        $pdf->ln();
        
        /**
         * on génère ensuite la liste de membres
         */
        $pdf->setFont('Arial', '', 9);
        
        for($i = 0; $i < count($ids); $i++) {
            
            $membre     = $mRepo->find($ids[$i]);
            
            //On récupère aussi son adresse principale
            $aService   = $this->get('interne_fichier.adressePrincipale');
            $adresse    = $aService->getPrincipale($membre);
            
            //Et ses contacts principaux
            $contact    = $this->get('interne_fichier.contactPrincipal');
            
            //On dessine les cellules
            $pdf->Cell(30,7, ucfirst($membre->getPrenom()), 0,0,'L');
            $pdf->Cell(30,7, ucfirst($membre->getFamille()->getNom()), 0,0,'L');
            $pdf->Cell(50,7, $adresse->getRue(), 0,0,'L');
            $pdf->Cell(16,7, $adresse->getNPA(), 0,0,'L');
            $pdf->Cell(32,7, $adresse->getLocalite(), 0,0,'L');
            $pdf->Cell(32,7, $contact->getTelephone($membre), 0,0,'L');
            $pdf->ln();
        }
        
        
        
        //Envoi du fichier
        $response = new Response(
            
            $pdf->Output(),
            Response::HTTP_OK,
            array('content-type' => 'application/pdf')
        );
    
        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste.pdf'
        );
    
        $response->headers->set('Content-Disposition', $d);
    
        return $response;
    }
    
    /**
     * exportation en XLS
     */
    public function exportXLSAction($ids) {
        
        //Récupération des variables
        $em = $this->getDoctrine()->getManager();
        $mRepo = $em->getRepository('InterneFichierBundle:Membre');
        $ids = explode(',', $ids);
        
        //On génère un nouveau fichier xls
        $excel = new \PHPExcel();
        $excel->createSheet();
        
        //On génère le header
        $headerStyle = array(
            
            'font' => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial'
            )
        );
        
        $excel->getActiveSheet()->setCellValue('A1', 'Prénom');
        $excel->getActiveSheet()->setCellValue('B1', 'Nom');
        $excel->getActiveSheet()->setCellValue('C1', 'Rue');
        $excel->getActiveSheet()->setCellValue('D1', 'NPA');
        $excel->getActiveSheet()->setCellValue('E1', 'Localité');
        $excel->getActiveSheet()->setCellValue('F1', 'Téléphone');
        $excel->getActiveSheet()->setCellValue('G1', 'E-Mail');
        $excel->getActiveSheet()->setCellValue('H1', 'Num. BS');
        $excel->getActiveSheet()->setCellValue('I1', 'Naissance');
        $excel->getActiveSheet()->setCellValue('J1', 'Inscription');
        $excel->getActiveSheet()->setCellValue('K1', 'Fonction');
        $excel->getActiveSheet()->setCellValue('L1', 'Unité/Groupe');
        
        //On formate les cellules
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
        
        $excel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        //On fournis ensuite les informations au fichier xls
        for($i = 0; $i < count($ids); $i++) {
            
            $membre = $mRepo->find($ids[$i]);
            $adresse = $this->get('interne_fichier.adressePrincipale')->getPrincipale($membre);
            $email = $this->get('interne_fichier.contactPrincipal')->getEmail($membre);
            $telephone = $this->get('interne_fichier.contactPrincipal')->getTelephone($membre);
            $attribution = $mRepo->findCurrentAttribution($membre->getId());
            
            $excel->getActiveSheet()->setCellValue('A' . ($i + 2), ucfirst($membre->getPrenom()));
            $excel->getActiveSheet()->setCellValue('B' . ($i + 2), ucfirst($membre->getFamille()->getNom()));
            $excel->getActiveSheet()->setCellValue('C' . ($i + 2), $adresse->getRue());
            $excel->getActiveSheet()->setCellValue('D' . ($i + 2), $adresse->getNPA());
            $excel->getActiveSheet()->setCellValue('E' . ($i + 2), $adresse->getLocalite());
            $excel->getActiveSheet()->setCellValueExplicit('F' . ($i + 2), $telephone, \PHPExcel_Cell_DataType::TYPE_STRING);
            $excel->getActiveSheet()->setCellValue('G' . ($i + 2), $email);
            $excel->getActiveSheet()->setCellValue('H' . ($i + 2), $membre->getNumeroBs());
            $excel->getActiveSheet()->setCellValue('I' . ($i + 2), $membre->getNaissance()->format('d.m.Y'));
            $excel->getActiveSheet()->setCellValue('J' . ($i + 2), $membre->getInscription()->format('d.m.Y'));
            $excel->getActiveSheet()->setCellValue('K' . ($i + 2), $attribution->getFonction()->getNom());
            $excel->getActiveSheet()->setCellValue('L' . ($i + 2), $attribution->getGroupe()->getNom());
        }
        
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        
        //Envoi du fichier
        $response = new Response();

	//Déclaration des headers
        $response->headers->set('Content-Type', 'application/xls');
        $response->headers->set('Content-Disposition', 'attachment;filename="liste.xls"');
        $response->sendHeaders();
        
        //Renvoi de la reponse
        $response->setContent($writer->save('php://output'));
        return $response;
        
    }
}