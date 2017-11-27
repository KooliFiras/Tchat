<?php
namespace UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @package UserBundle/Controller
 * @RouteResource("login", pluralize=false)
 */
class LoginController extends FOSRestController implements ClassResourceInterface
{
    public function postAction()
    {
        // handled by Lexik JWT Bundle
        $user= $this->getUser();
        $user->setLastLogin(new \DateTime());
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($user);
        throw new \DomainException('You should never see this');
    }
}