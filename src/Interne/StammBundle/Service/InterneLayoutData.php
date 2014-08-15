<?php

namespace Interne\StammBundle\Service;

/**
 * Le service InterneLayoutData permet de charger les données relatives
 * utiles pour l'affichage du layout, et centraliser du code utilisé dans
 * chaque action de controller utilisant le layout principal
 */
 
use Doctrine\ORM\EntityManager;
 
class InterneLayoutData
{
	/**
	 * Retourne la liste des Evenements pour le mois courant, et si la date
	 * d'aujourd'hui est à 5 jours du mois suivant, on prend aussi ceux-là
	 */
	public function getEvenements($repository) {
		
        return $repository->findEvenementsMonth();
	}
}