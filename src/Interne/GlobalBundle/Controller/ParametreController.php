<?php

namespace Interne\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Interne\GlobalBundle\Entity\Parametre;

class ParametreController extends Controller
{
    /*
     * Cette méthode permet l'affichage d'un groupe de parametre.
     * Le groupe est un argument de la methode a passer en url.
     *
     * A noter que l'ajout de parametre se fait automatiquement du
     * moment que le parametre est présant dans la méthode
     * "function listeParametre()".
     *
     */
    public function indexAction($groupe)
    {
        $em = $this->getDoctrine()->getManager();
        $parametres = $em->getRepository('InterneGlobalBundle:Parametre')->findByGroupe($groupe);


        $listeParametres = $this->listeParametre();

        /*
         * On controle que la liste est compléte. On ajoute à la BBD si nécaissaire
         */
        foreach($listeParametres as $parametre)
        {
            $found = false;
            foreach($parametres as $parametreBDD)
            {
                if($parametreBDD->getName() == $parametre['name'])
                {
                    $found = true;
                }
            }
            if(!$found)
            {
                $newParametre = new Parametre($parametre);
                $em->persist($newParametre);
                $parametres[] = $newParametre;
            }
        }
        $em->flush();

        return $this->render('InterneGlobalBundle:Parametre:index.html.twig', array('parametres'=>$parametres));
    }

    /*
     * Edition en ajax des parametres depuis la page d'affichage
     */
    public function updateAjaxAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $id = $request->request->get('id');
            $value = $request->request->get('value');
            $type = $request->request->get('type');

            $em = $this->getDoctrine()->getManager();
            $parametre = $em->getRepository('InterneGlobalBundle:Parametre')->find($id);

            switch($type)
            {
                case 'string':
                    $parametre->setString($value);
                    break;
                case 'number':
                    $parametre->setNumber($value);
                    break;
                case 'text':
                    $parametre->setText($value);
                    break;
                case 'choice':
                    $parametre->setChoice($value);
                    break;
            }
            $em->flush();
            return new Response();
        }
        return new Response();

    }

    /*
     * La liste de parametre permet l'ajout de nouveaux parametre (trié par groupe)
     *
     */
    private function listeParametre()
    {
        /*
         * ICI est crée la liste des parametres
         */
        $listeParametres = array();
        /*
         *  QUELQUES EXEMPLES
         *
        $listeParametres[] = array('name'=>'Exemple_text','type'=>'text','labelName'=>'Text','value'=>'text');
        $listeParametres[] = array('name'=>'Exemple_string','type'=>'string','labelName'=>'String','value'=>'string');
        $listeParametres[] = array('name'=>'Exemple_number','type'=>'number','labelName'=>'Nombre','value'=>77);
        $listeParametres[] = array('name'=>'Exemple_choice','type'=>'choice','labelName'=>'choice','value'=>array('choice1','choice2'));
        */

        /*
         * GROUPE => Facture Bundle
         */

        $listeParametres[] = array( 'name'=>'impression_ccp_bvr',
                                    'groupe' => 'facture',
                                    'type'=>'string',
                                    'labelName'=>'Numéro de compte CCP (BVR)',
                                    'value'=>'01-66840-7');
        $listeParametres[] = array( 'name'=>'impression_ccp_bv',
                                    'groupe' => 'facture',
                                    'type'=>'string',
                                    'labelName'=>'Numéro de compte CCP (BV)',
                                    'value'=>'10-1915-8');
        $listeParametres[] = array( 'name'=>'impression_adresse',
                                    'groupe' => 'facture',
                                    'type'=>'text',
                                    'labelName'=>'Adresse du groupe scout',
                                    'value'=>null);
        $listeParametres[] = array( 'name'=>'impression_mode_payement',
                                    'groupe' => 'facture',
                                    'type'=>'choice',
                                    'labelName'=>'Choix du mode de payement',
                                    'value'=>array('BV','BVR'));
        $listeParametres[] = array( 'name'=>'impression_texte_facture',
                                    'groupe' => 'facture',
                                    'type'=>'text',
                                    'labelName'=>'Texte sur les factures',
                                    'value'=>null);


        return $listeParametres;
    }





}