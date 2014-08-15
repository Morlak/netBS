<?php

namespace Interne\StructureBundle\Services;


class DateFilter extends \Twig_Extension
{
	
	public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('datum', array($this, 'dateFilter')),
        );
    }
    
	/**
	 * Utilisé dans twig, permet de changer l'affichage des dates de fin
	 * fixes, par une case vide, au lieu d'afficher 2199
	 */
	public function dateFilter($date)
    {

        if(is_null($date))
        	return "";

        else return $date->format('d.m.Y');
    }
    
    
	public function getName()
    {
        return 'date_filter';
    }
}