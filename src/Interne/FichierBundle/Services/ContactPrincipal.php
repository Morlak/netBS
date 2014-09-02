<?php

namespace Interne\FichierBundle\Services;

class ContactPrincipal {
	
    /**
     * Ce service permet de renvoyer un e-mail et un téléphone "principal".
     * Si le membre a un numéro de téléphone et un email perso, ceux-ci sont
     * considérés comme principaux. Sinon, c'est ceux de la famille qui sont
     * fournis
     */
    public function getEmail($membre) {
        
        return  ($membre->getContact()->getEmail() == null || $membre->getContact()->getEmail() == '')
                ? $membre->getFamille()->getContact()->getEmail()
                : $membre->getContact()->getEmail();
    }
    
    public function getTelephone($membre) {
        
        return  ($membre->getContact()->getTelephone() == null || $membre->getContact()->getTelephone() == '')
                ? $membre->getFamille()->getContact()->getTelephone()
                : $membre->getContact()->getTelephone();
    }
}