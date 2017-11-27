<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $pusher = $this->container->get('gos_web_socket.zmq.pusher');
        //push(data, route_name, route_arguments)
        $user=$this->getUser();

        $pusher->push(['my_data' => 'welcome '.$user->getUsername() ], 'acme_topic');

        return $this->render('AppBundle::index.html.twig');
    }
}
