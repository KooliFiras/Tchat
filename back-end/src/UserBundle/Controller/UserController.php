<?php

namespace UserBundle\Controller;


use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Request;


/**
 * @package UserBundle/Controller
 * @RouteResource("user", pluralize=false)
 */
class UserController  extends FOSRestController implements ClassResourceInterface
{

    public function getwhoIsOnlineActionAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->getActive();

        return $users;
    }


    /**
     * Displays the authenticated participant inbox.
     * @Annotations\Get("/participants")
     * @return array
     */
    public function getParticipantsAction(){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            '
            SELECT  u.id, u.username, u.email
            FROM  UserBundle:User u
          
           ');

        $user = $query->getResult();

        return $user;
    }


    /**
     * Displays the authenticated participant inbox.
     * @Annotations\Get("/participant_details/{userId}")
     * @param $userId
     * @return array
     */
    public function getParticipantDetailsAction($userId){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            '
            SELECT  u.username, u.email
            FROM  UserBundle:User u
            WHERE u.id= :user_id
            
           ')->setParameter('user_id',$userId );

        $user = $query->getResult();

        return $user;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function patchProfileImageAction(Request $request){

        $user = $this->getUser();
        $filename = $request->get('filename');
        $user->setFilename($filename);
        $file = base64_decode($request->get('file'));
        $dir = __DIR__.'/../../../web/images/users/';
        file_put_contents($dir.$filename, $file);
        $em = $this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return $user;

    }


}