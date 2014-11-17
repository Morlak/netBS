<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Interne\StammBundle\Entity\News;



use Interne\FichierBundle\Entity\Membre;
use Interne\FichierBundle\Entity\Geniteur;
use Interne\FichierBundle\Entity\Famille;
use Interne\FichierBundle\Entity\Adresse;
use Interne\SecurityBundle\Entity\User;

use Interne\StructureBundle\Entity\Groupe;
use Interne\StructureBundle\Entity\Type;
use Interne\StructureBundle\Entity\Attribution;
use Interne\StructureBundle\Entity\Fonction;


class ValidatorController extends Controller
{

    /**
     * Cette méthode permet d'afficher l'ensemble des modifications sur la base de donnée en attente de
     * validation. L'ensemble du validator n'est accessible qu'à partir de certains roles, définis dans construct
     */
    public function dashboardAction() {

        $validations = $this->getDoctrine()->getManager()->getRepository('InterneGlobalBundle:Validation')->findAll();

        return $this->render('InterneGlobalBundle:Validation:dashboard.html.twig', array(

            'validations' => $validations,
        ));
    }

    /**
     * retourne la classe qui avait été sérializée, et si le paramètre modif est à true, retourne également l'entité
     * courante, non mddifiée
     */
    public function getExtendedDataAction($id) {

        $em         = $this->getDoctrine()->getManager();
        $vRepo      = $em->getRepository('InterneGlobalBundle:Validation');
        $validation = $vRepo->find($id);
        $returned   = array(

            'statut'    => $validation->getStatut(),
            'donnees'   => array()
        );

        if($validation->getStatut() == "MODIFICATION") {

            //On récupère aussi l'ancienne entité, pour comparer les données modifiées
            $oldEntity      = $em->getRepository($validation->getRepo())->find($validation->getEntityId());

            //On va génerer un array de données sous la forme '1' => Ancienne donnée, nouvelle donnée, id, ...
            foreach($validation->getModifications() as $k => $modif) {

                //On génère la méthode de récupération de donnée
                $fnData = explode('.', $modif->getPath());
                $cursor = $oldEntity;

                for($i = 0; $i < count($fnData); $i++) {

                    if ($fnData[$i] != '') {
                        $fn = 'get' . $fnData[$i];
                        $cursor = $cursor->$fn();
                    }
                }

                $getter = 'get' . $modif->getChamp();

                $user = $modif->getUser()->getMembre();

                $champ = '';
                if($modif->getPath() == '') $champ = $validation->getEntityName() . ' - <b>' . $modif->getChamp() . '</b>';
                else $champ = $validation->getEntityName() . ' - ' . str_replace('.', ' - ', $modif->getPath()) . ' - <b>' . $modif->getChamp() . '</b>';

                $returned['donnees'][$k] = array(
                    'champ'     => $champ,
                    'ancien'    => $this->parseValue($cursor->$getter(), true),
                    'neuf'      => $this->parseValue($modif->getValeur(), true),
                    'id'        => $modif->getId(),
                    'date'      => $modif->getDate()->format('d.m.Y'),
                    'user'      => ucfirst(strtolower($user->getPrenom() . ' ' . $user->getNom()))
                );
            }
        }

        else {

            //On construit un tableau simple
            foreach($validation->getModifications() as $k => $modif) {

                $user = $modif->getUser()->getMembre();

                $champ = '';
                if($modif->getPath() == '') $champ = $validation->getEntityName() . ' - <b>' . $modif->getChamp() . '</b>';
                else $champ = $validation->getEntityName() . ' - ' . str_replace('.', ' - ', $modif->getPath()) . ' - <b>' . $modif->getChamp() . '</b>';

                $returned['donnees'][$k] = array(
                    'champ'     => $champ,
                    'neuf'      => $this->parseValue($modif->getValeur(), true),
                    'id'        => $modif->getId(),
                    'date'      => $modif->getDate()->format('d.m.Y'),
                    'user'      => ucfirst(strtolower($user->getPrenom())) . ' ' . ucfirst(strtolower($user->getNom()))
                );
            }
        }

        return new JsonResponse($returned);
    }

    /**
     * la méthode remove permet de supprimer des entités en attente de validation
     */
    public function removeAction($ids) {

        $ids = explode('-', $ids);
        $em  = $this->getDoctrine()->getManager();
        $vRepo = $em->getRepository('InterneGlobalBundle:Validation');

        //Pour chaque entrée du tableau, on supprime la validation correspondante
        for($i = 0; $i < count($ids); $i++) {

            $entity = $vRepo->find($ids[$i]);
            $em->remove($entity);
        }

        $em->flush();
        return new JsonResponse($ids);
    }

    /**
     * Permet de persister une masse de validation d'un coup. Pour chaque
     */
    public function persistValidationAction($ids) {

        $ids = explode('-', $ids);

        $em         = $this->getDoctrine()->getManager();
        $vRepo      = $em->getRepository('InterneGlobalBundle:Validation');

        for($i = 0; $i < count($ids); $i++) {

            $validation = $vRepo->find($ids[$i]);
            $repo       = $em->getRepository($validation->getRepo());


            //En fonction du statut, on fait quelque chose de différent
            /*
             * Espace SUPPRESSION
             * On va simplement récupérer l'entité liée, et la supprimer de l'em
             */
            if($validation->getStatut() == 'SUPPRESSION') {

                $entity = $repo->find($validation->getEntityId());
                $em->remove($entity);
            }


            /*
             * CREATION - MODIFICATION
             * On va instancier un nouvel objet vide que l'on va hydrater, puis persister
             */
            else {

                /*
                 * On va d'abord récupérer l'entité que l'on souhaite modifier, soit un objet vide, soit un existant
                 * que l'on récupère
                 */
                $objet = null;
                if($validation->getStatut() == "CREATION") {

                    //On va génerer un nouvel objet
                    $objName    = $validation->getFullClass();
                    $objet      = new $objName();
                }

                else {

                    //Modification, on récupère l'objet existant
                    $objet = $em->getRepository($validation->getRepo())->find($validation->getEntityId());
                }

                /*
                 * A cette étape, on a un objet valide sur lequel travailler. On va donc itérer sur chaque modification
                 * liées à la validation, formater les valeurs, et les appliquer sur cet objet, que l'on pourra ensuite
                 * persister.
                 */
                foreach($validation->getModifications() as $modif) {


                    //On génère les getters
                    $fnData = explode('.', $modif->getPath());
                    $cursor = $objet;


                    for ($i = 0; $i < count($fnData); $i++) {

                        if ($fnData[$i] != '') {

                            $fn = 'get' . $fnData[$i];
                            $cursor = $cursor->$fn();
                        }
                    }


                    $setter = 'set' . $modif->getChamp();
                    $cursor->$setter($this->parseValue($modif->getValeur())); //Magique

                }


                $em->persist($objet);

                //On supprimme la validation
                //$em->remove($validation);

            }
        }

        $em->flush();
        return new JsonResponse($ids);
    }

    public function testAction() {

/*
        $persistor = $this->get('global.persistor');


        $persistor->persistation('InterneFichierBundle.Membre.Adresse.Rue', 'Avenue des hommes qui pèsent 42', 8);


        $this->getDoctrine()->getManager()->flush();

*/
        $this->getExtendedDataAction(5);
        return new Response('<body></body>');

    }

    /**
     * Cette méthode permet de parser les informations contenues dans la valeur d'une modif, dans le cas d'une relation
     * Elle va retourner soit l'objet lié, soit une représentation textuelle de l'objet pour affichage. Sinon elle va tenter
     * de transformer la valeur, comme pour une date.
     * @param $val la valeur a parser
     * @return mixed
     */
    private function parseValue($val, $toString = false) {

        $data = explode('__', $val);

        if($data[0] == 'ENTITY') {

            /*
             * Relation
             * --------
             * On va récupérer l'objet à lier et le renvoyer si il en existe bel et bien un
             */
            $entity = $this->getDoctrine()->getManager()->getRepository($data[1])->find($data[2]);
            if($entity == null) return $val; //Test de sortie immediate

            if(!$toString) return $entity;
            else {

                /*
                 * On va chercher une méthode représentative parmi les suivantes :
                 * - getNom (prioritaire)
                 * - getPrenom
                 */
                if (method_exists($entity, 'getNom'))
                    return $entity->getNom();

                elseif(method_exists($entity, 'getPrenom'))
                    return $entity->getPrenom();

                else
                    return get_class($entity);
            }
        }

        /*
         * On a pas affaire à une relation, on va donc travailler sur la valeur.
         */
        else {

            //On regarde si la valeur est une date
            if (\DateTime::createFromFormat('Y-m-d G:i:s', $val) !== FALSE) {

                if($toString == false) return new \Datetime($val);
                else return $val;
            }


            else
                return $val; //Sinon on renvoie simplement la valeur non modifiée


        }
    }
}
