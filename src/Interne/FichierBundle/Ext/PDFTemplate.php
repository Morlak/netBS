<?php

/**
 * pose le template d'une page PDF basique,
 * c'est-à-dire avec un header et footer
 */

namespace Interne\FichierBundle\Ext;

use Interne\FichierBundle\Ext\FPDF;

class PDFTemplate extends FPDF
{
    function header()
    {
        // Logo
        $this->Image(__DIR__ . '/../../../../web/static/images/main_logo.png',10,10,16,16);
        $this->Cell(20);
        
        //Titre brigade de sauvabelin
        $this->setFont('arial', '', 10);
        $this->Cell(30,20,'Brigade de Sauvabelin',0,1);
        $this->Cell(0,10,'',0,1);
        
    }
}
