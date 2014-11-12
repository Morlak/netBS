<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidatorController extends Controller
{

    /**
     * Cette méthode permet d'afficher l'ensemble des modifications sur la base de donnée en attente de
     * validation. L'ensemble du validator n'est accessible qu'à partir de certains roles, définis dans construct
     */
    public function dashboardAction() {

/*
        $news = $this->getDoctrine()->getManager()->getRepository('InterneStammBundle:News')->find(1);
        $news->setTitre('un nouveau titre');
        $news->setDate(new \Datetime("now"));
        $persistor = $this->get('global.persistor');
        $persistor->safePersist($news);
        $persistor->safeFlush();
*/

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

        $em     = $this->getDoctrine()->getManager();
        $vRepo  = $em->getRepository('InterneGlobalBundle:Validation');
        $valida = $vRepo->find($id);
        $entity = $valida->getEntity();

        if($valida->getStatut() != "MODIFICATION") return new JsonResponse($entity);
        else {

            //On récupère aussi l'ancienne entité, pour comparer les données modifiées
            $class = explode('\\', $valida->getClassIdentifier());
            $repoName = $class[0] . $class[1] . ':' . $class[3];

            $serializer = $this->get('jms_serializer');
            $deserialized = $serializer->deserialize($entity, $valida->getClassIdentifier(), 'json');

            $current = $em->getRepository($repoName)->find($deserialized->getId());
            $jsoned  = $serializer->serialize($current, 'json');

            return new JsonResponse(array($entity, $jsoned));
        }
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
     * Permet de persister une masse de validation d'un coup. On va donc d'abbord persister
     * l'entité contenue dans la validation, puis supprimer l'entité validation, qui n'aura plus
     * lieu d'être
     */
    public function persistAction($ids) {

        $ids = explode('-', $ids);

        $em         = $this->getDoctrine()->getManager();
        $vRepo      = $em->getRepository('InterneGlobalBundle:Validation');
        $serializer = $this->get('jms_serializer');

        for($i = 0; $i < count($ids); $i++) {


            $validation = $vRepo->find($ids[$i]);
            $entity = $serializer->deserialize($validation->getEntity(), $validation->getClassIdentifier(), 'json');

            //On va ensuite regarder ce qu'il faut faire avec l'entité, ajouter/modifier ou supprimer
            if($validation->getStatut() == 'SUPPRESSION') $em->remove($entity);
            else if($validation->getStatut() == 'CREATION') $em->persist($entity);
            else $em->merge($entity); //Modification, on merge l'entité

            //On supprimme ensuite l'entité validation de l'em
            $em->remove($validation);

        }

        $em->flush();

        return new JsonResponse($ids);
    }
}
