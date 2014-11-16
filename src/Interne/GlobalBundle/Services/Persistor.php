<?php

namespace Interne\GlobalBundle\Services;

use Interne\GlobalBundle\Entity\Validation;
use Interne\GlobalBundle\Entity\Modification;


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
     * @param $entity l'entité
     * @param $value la valeur
     * @param null $id l'id de l'entité. Peut prendre un paramètre hash md5 pour le cas d'une création, garder le lien
     *              avec l'entité
     * @param null $statut le statut
     */
    public function persistation($entity, $value, $id = null, $statut = null) {

        $data       = explode('.', $entity);
        $repo       = $data[0];
        $entity     = $data[1];
        $user       = $this->context->getToken()->getUser();
        $champ      = $data[count($data) - 1];

        //On formate la valeur, par exemple si c'est une datetime
        if ($value instanceof \DateTime) $value = $value->format('Y-m-d H:i:s');


        //On analyse si c'est un ajout, une modification ou une suppression
        if($id == null || !is_numeric($id)) { $statut = 'CREATION'; $id = ($id == null) ? time() : $id; } //l'id prend un hash aléatoire si rien n'est précisé
        else if($statut != null) $statut = 'SUPPRESSION';
        else $statut = 'MODIFICATION';


        //On traite les données pour générer un path propre
        $path = '';
        for($i = 2; $i < (count($data) - 1); $i++) {

            $path .= ($i == (count($data) - 2)) ? $data[$i] : $data[$i] . '.';
        }

        $fullClassPieces    = preg_split('/(?=[A-Z])/',$repo);
        //NOM DE BUNDLE EN TROIS MOTS : InterneYoloBundle, INTERDIT : InterneYoloSwagBundle
        $fullClass          = $fullClassPieces[1] . '\\' . $fullClassPieces[2] . $fullClassPieces[3] . '\\Entity' . '\\' . $entity;

        //On va chercher si une validation sur l'objet existe déjà pour travailler dessus
        $identifier = md5($repo . $id);
        $validation = null;

        if($this->em->getRepository('InterneGlobalBundle:Validation')->findByIdentifier($identifier) == null) {

            $validation = new Validation();
            $validation->setRepo($repo . ':' . $entity);
            $validation->setIdentifier($identifier);
            $validation->setEntityId($id);
            $validation->setStatut($statut);
            $validation->setEntityId($id);
            $validation->setEntityName($entity);
            $validation->setFullClass($fullClass);

            $validation->addModification($this->generateModification($path, $champ, $value, $user));

        }

        else {

            /*
             * Si une modification pour ce champ existe déjà, 2 cas de figure :
             * - soit c'est le même utilisateur qui a modifié par dessus, on ecrase l'ancienne modification
             * - soit c'est quelqu'un d'autre, on crée une nouvelle Modification
             */
            $validation = $this->em->getRepository('InterneGlobalBundle:Validation')->findByIdentifier($identifier)[0];
            $modifs     = $validation->getModifications();
            $exist      = false;
            $id         = null;

            foreach($modifs as $k => $modif) {

                if($modif->getUser() == $user && $modif->getChamp() == $champ && $modif->getPath() == $path) {
                    $exist = true;
                    $id    = $k;
                }
            }

            if($exist)
                $validation->getModifications()[$id]->setValeur($value); //On modifie juste la valeur à modifier
            else
                $validation->addModification($this->generateModification($path, $champ, $value, $user));
        }


        //Et finalement on persiste la validation
        $this->em->persist($validation);
    }

    public function modifOneField($entity, $id, $value) {

        $data       = explode('.', $entity);
        $repo       = $data[0];
        $entity     = $data[1];

        $entity     = $this->em->getRepository($repo . ':' . ucfirst(strtolower($entity)))->find($id);
        $cursor     = $entity;

        for($i = 2; $i < (count($data) - 1); $i++) {

            $fn     = 'get' . ucfirst(strtolower($data[$i]));
            $cursor = $cursor->$fn();
        }

        $setter     = 'set' . ucfirst(strtolower($data[count($data) - 1]));
        $cursor->$setter($value);

        var_dump($cursor);

    }

    /**
     * Génère un objet modification avec les données passées
     * @param string $path
     * @param string $champ
     * @param mixed $value
     * @param User $user
     * @return Modification
     */
    private function generateModification($path, $champ, $value, $user) {

        //On génère une nouvelle modification
        $modification   = new Modification();
        $modification->setDate(new \Datetime());
        $modification->setPath($path);
        $modification->setChamp($champ);
        $modification->setValeur($value);
        $modification->setUser($user);

        return $modification;
    }
}