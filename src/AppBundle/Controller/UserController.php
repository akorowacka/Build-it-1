<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;

use AppBundle\Entity\User;

class UserController extends FOSRestController
{
  /**
   * Fetch all users from database
   *
   * @ApiDoc(
   *  resource=true,
   *  description="Fetch all users from database",
   *  statusCodes={
   *         200="Returned when successful",
   *         404={
   *           "Returned when the user is not found",
   *         }
   *     }
   * )
   */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:User')->findAll();
        if(!$entities)
        {
             return $this->view(null, 404);
        }

        return $this->view($entities, 200);
    }

    /**
     * Fetch all users from database
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Fetch user with current ID from database",
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the user is not found",
     *         }
     *     }
     * )
     */
    public function getUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:User')->find($userId);

        if(!$entities)
        {
             return $this->view(null, 404);
        }

        return $this->view($entities, 200);
    }

    /**
     * Fetch all users from database
     * @Get("/users/login/{userLogin}")
     * @ApiDoc(
     *  resource=true,
     *  description="Fetch user with current login from database",
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when the user is not found",
     *         }
     *     }
     * )
     */
    public function getUserLoginAction($userLogin)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:User')->findByEmail($userLogin);

        if(!$entities)
        {
             return $this->view(null, 404);
        }

        return $this->view($entities, 200);
    }

    /**
     * Create a User from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="name", nullable=false, description="Name")
     * @RequestParam(name="surname", nullable=false, description="Surname")
     * @RequestParam(name="email", nullable=false, description="Email")
     * @RequestParam(name="password", nullable=false, description="Password")
     * @RequestParam(name="avatar_url", nullable=true, description="Avatar url")
     *
     * @return View
     */

    public function postUsersAction(ParamFetcher $paramFetcher) {
        $user = new User();

        $user->setName($paramFetcher->get('name'));
        $user->setSurname($paramFetcher->get('surname'));
        $user->setEmail($paramFetcher->get('email'));
        $user->setPassword($paramFetcher->get('password'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        new Response('Saved new product with id '.$user->getId());
    }


}
