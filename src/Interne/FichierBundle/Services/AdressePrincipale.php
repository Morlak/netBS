<?php

namespace Interne\FichierBundle\Services;

class AdressePrincipale {
	
	/**
	 * détermine l'adresse principale du membre passé en paramètre
	 * @return 'membre' si l'adresse du membre est complète
	 * 		   'famille' sinon
	 */
	function checkPrincipale($membre) {
		
		if(is_null($membre->getAdresse()->getRue()) && is_null($membre->getAdresse()->getLocalite())) {
			
			return 'famille';
		}
		
		else
			return 'membre';
	}
}