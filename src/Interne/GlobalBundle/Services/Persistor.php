<?php

namespace Interne\GlobalBundle\Services;

use Interne\GlobalBundle\Entity\Validation;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Persistor
{

    private $em;
    private $context;
    private $serializer;

    /**
     * la méthode persist va, en fonction du ROLE de l'utilisateur, soit persister les modifications
     * qu'il réalise, soit les envoyer dans le validator, avant d'être persistées par un utilisateur
     * avec un rang plus élevé.
     */
    public function __construct($context, $em, $serializer)
    {
        $this->context      = $context;
        $this->serializer   = $serializer;
        $this->em           = $em;
    }

    /**
     * Cette méthode renvoie un boolean pour savoir si l'utilisateur courrant à les roles nécessaires pour valider
     * automatiquement ou PAS.
     */
    public function hasValidatorRoles() {

        if( $this->context->isGranted('ROLE_ADMIN') ||
            $this->context->isGranted('ROLE_SECRETARIAT') ||
            $this->context->isGranted('ROLE_VALIDATOR')) {

            return true;
        }
        else return false;
    }

    /**
     * la méthode safePersist permet de persister une entité en fonction des roles de l'utilisateur. Ainsi, les données
     * modifiées devront ou non passer par le validator, et être validées par un utilisateur supérieur
     *
     * @param $entity l'entité sur laquelle travailler
     */
    public function safePersist($entity, $state = null) {

        /*
        if( $this->context->isGranted('ROLE_ADMIN') ||
            $this->context->isGranted('ROLE_SECRETARIAT') ||
            $this->context->isGranted('ROLE_VALIDATOR')) {

            $this->em->persist($entity); //On persiste dans l'em
        }

        */

        /*
         * l'utilisateur n'a pas les accès nécessaires, on va serializer l'entité, puis l'ajouter au validator, en
         * ajoutant une directive empêchant l'entité d'être à nouveau modifiée jusqu'à validation
         */

        //else {

            //On chope le nom de la classe
            $class      = explode('\\', get_class($entity));

            $serializer = $this->serializer;
            $serialized = $serializer->serialize($entity, 'json');

            $user       = $this->context->getToken()->getUser();

            $validation = new Validation();
            $validation->setDate(new \Datetime("now"));
            $validation->setUser($user);

            /*
             * le statut peut prendre 3 états différents,
             * - CREATION qui indique la création d'une entité
             * - MODIFICATION si modification
             * - SUPPRESSION si on supprimme l'entité
             */
            $statut = ($state == null) ? (($entity->getId() == null) ? 'CREATION' : 'MODIFICATION')
                                       : ($state);
            $validation->setClassIdentifier(get_class($entity));
            $validation->setClassName($class[3]);
            $validation->setStatut( $statut );
            $validation->setEntity($serialized);

            /*
             * Si c'est une modification, on doit pas oublier de détacher l'entité de l'EM, pour éviter qu'elle
             * soit persistée aussi
             */
            if($this->em->contains($entity)) $this->em->detach($entity);

            $this->em->persist($validation);



        //}
    }

    /**
     * Permet de supprimer une entité en fonction des roles
     * @param $entity l'entité à supprimer
     */
    public function safeRemove($entity) {

        $this->safePersist($entity, 'REMOVE');
    }

    /**
     * safeFlush va flusher l'entity manager utilisé par le service pour éviter des conflits
     */
    public function safeFlush() {

        $this->em->flush();
    }
}