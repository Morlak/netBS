<?php

namespace Interne\GlobalBundle\Services;

class Pdf extends \fpdf\FPDF
{
    /*
     * Surcharge de la fonction pour prendre en charge
     * les accents du français avec 'utf8_decode'
     */
    function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='') {
        parent::Cell($w,$h, utf8_decode($txt), $border,$ln,$align,$fill,$link);
    }



}