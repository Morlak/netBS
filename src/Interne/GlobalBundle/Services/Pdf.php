<?php

namespace Interne\GlobalBundle\Services;

class Pdf
{

    private $pdf;

    public function __construct() {

        $this->pdf = new \fpdf\FPDF();
    }

    public function getPdf() {

        return $this->pdf;
    }
}