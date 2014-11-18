<?php

namespace Interne\GlobalBundle\Services;

use Interne\GlobalBundle\Entity\Validation;
use Interne\GlobalBundle\Entity\Modification;


class Persistor
{

    private $em;
    private $context;
    private $reader;
    private $serializer;

    /**
     * la méthode persist va, en fonction du ROLE de l'utilisateur, soit persister les modifications
     * qu'il réalise, soit les envoyer dans le validator, avant d'être persistées par un utilisateur
     * avec un rang plus élevé.
     */
    public function __construct($context, $em, $serializer, $annotationReader)
    {
        $this->context      = $context;
        $this->serializer   = $serializer;
        $this->em           = $em;
        $this->reader       = $annotationReader;
    }


    /**
     * @param $entity l'entité
     * @param $value la valeur
     * @param null $id l'id de l'entité. Peut prendre un paramètre hash md5 pour le cas d'une création, garder le lien
     *              avec l'entité
     * @param null $statut le statut
     */
    public function persistation($entity, $value, $id = null, $statut = null) {

        $data       = explode('_', $entity);
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

    /*
    public function fullEntityPersistation($object) {

        $reader             = $this->reader;
        $user               = $this->context->getToken()->getUser();
        $emptyObjectName    = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($object));
        $emptyObject        = new $emptyObjectName();
        $metaData = $this->em->getClassMetadata($emptyObjectName);


        /*
         * En premier lieu, on va checker si l'entité est nouvellement créée, ou si c'est une modification
         * de la dite entité. On va également génerer l'objet validation
         *
        $statut             = ($object->getId() === null) ? 'CREATION': 'MODIFICATION';
        $classData          = explode('\\', $emptyObjectName);
        $repo               = $classData[0] . $classData[1] . ':' . $classData[3];
        $id                 = ($statut == 'CREATION') ? 0 : $object->getId();
        $identifier         = md5($repo . $id);

        $validation         = new Validation();
        $validation->setStatut($statut);
        $validation->setEntityId($id);
        $validation->setFullClass($emptyObjectName);
        $validation->setIdentifier($identifier);
        $validation->setEntityName($classData[3]);

        /*
         * On s'interesse à deux entrées de la classe metaData :
         * - fieldMappings qui contient les attributs directs, sans relations quelconque
         * - associationMappings qui contient les attributs par relation
         *
         * Dans le cas d'une création, on va iterer sur chaque proprieté, et pour chacune, créer un objet
         * Modification équivalent.
         * Dans le cas d'une modification, on itère sur chaque élément, et si la valeur est différente, on
         * crée une modification
         *
        $entity = null;
        if($statut == "MODIFICATION")
            $entity = $this->em->getRepository($repo)->find($id);

        /*
         * On commence par iterer sur les proprietés simples
         *
        foreach($metaData->fieldMappings as $field) {

            $getter = 'get' . ucfirst($field['fieldName']);
            $value  = $object->$getter();

            /*
             * On va iterer sur chaque élément si c'est une modification. On récupère donc l'entité actuelle, comparer
             * chaque valeur, et créer une Modification là ou ca diffère. Si c'est une creation, on crée des modifications
             * partout où c'est pas NULL
             *
            $modifChangePas = true;

            if($statut == "MODIFICATION")
                $modifChangePas = !($entity->$getter() === $value); //On utilise le === valable sur tout type de champ

            //On génère une Modification si nécessaire
            if(($statut == "CREATION" && $object->$getter() !== null ) || !$modifChangePas) {

                /*
                 * Comme on est dans des attributs direct, $path est vide, on a donc aucun traitement particulier
                 * à réaliser
                 *
                $validation->addModification(
                    $this->generateModification('', ucfirst($field['fieldName']), $value, $user)
                );
            }
        }

        /*
         * A ce stade, on a géneré toutes les modifications pour les attributs directs, on va passer aux attributs
         * par relation. On va donc iterer sur chaque élément
         *
        foreach($metaData->associationMappings as $association) {

            $getter = 'get' . ucfirst($association['fieldName']);

            /*
             * En premier lieu on génère les informations de base, genre le path et le nom
             * du champ
             *
            $path = ucfirst($association['fieldName']);

            $cursor = $object->$getter();

            /*
             * En premier lieu, on vérifie que l'entité liée ne soit pas nulle, sinon ca sert à rien
             *
            if($cursor != null && !$this->isEntityNull($cursor)) {

                $this->recursiveScanEntity($cursor);
            }
        }
    }

    /**
     * Cette petite méthode va tester si l'entité passée est nulle, c'est-à-dire si l'ID est nulle
     * et si l'une des fonction suivante existe et renvoie nulle
     *
    private function isEntityNull($entity) {

        if($entity->getId() != null) return false;

        if(method_exists($entity, 'getNom')) //Famille, Type, Groupe...
            return ($entity->getNom() == null);
        else if(method_exists($entity, 'getPrenom')) //membre
            return ($entity->getPrenom() == null);
        else if(method_exists($entity, 'getRue')) //Adresse
            return ($entity->getRue() == null);

        else return false;
    }


    /**
     * La méthode recursiveScanEntity va iterer sur chaque valeur de l'entité passée
     * en paramètre, scanner chaque valeur, génerer le path correspondant, et les Modifications nécessaires
     *
    private function recursiveScanEntity($entity) {

        //On va iterer sur chaque méthode de l'objet passé, vérifier la nature des éléments,
    }
    */
}