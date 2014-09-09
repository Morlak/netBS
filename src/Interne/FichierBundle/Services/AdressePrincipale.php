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
	
	
	/**
	 * cette méthode renvoie un objet adresse entier de l'adresse principale
	 * du membre.
	 * - Si l'adresse du membre est inexistante ou non facturable, elle prend celle de la famille
	 * - Si celle de la famille est inexistante ou non facturable, elle prend celle du premier parent
	 *   dont l'adresse est facturable
	 */
	function getPrincipale($membre) {
		
		if( (is_null($membre->getAdresse()->getRue()) && is_null($membre->getAdresse()->getNPA()) ) || !$membre->getAdresse()->getFacturable()) {
			
			//Adresse du membre vide, on passe à la famille
			if( (is_null($membre->getFamille()->getAdresse()->getRue()) && is_null($membre->getFamille()->getAdresse()->getNPA()) ) || !$membre->getFamille()->getAdresse()->getFacturable()) {
				
				//L'adresse de la famille est inexistante également, on recherche donc une adresse chez les parents
				//en commencant par la mère
				if( (is_null($membre->getFamille()->getMere()->getAdresse()->getRue()) && is_null($membre->getFamille()->getMere()->getAdresse()->getNPA()) ) || !$membre->getFamille()->getMere()->getAdresse()->getFacturable())
					return $membre->getFamille()->getPere()->getAdresse();
				
				else
					return $membre->getFamille()->getMere()->getAdresse();
			
			}
			
			else
				return $membre->getFamille()->getAdresse();
		}
		
		else
			return $membre->getAdresse();
	}
}