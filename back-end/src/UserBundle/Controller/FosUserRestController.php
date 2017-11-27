<?php

namespace UserBundle\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @RouteResource("User", pluralize=false)
 */
class FosUserRestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction()
    {
        if (null === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        return true;
    }
}