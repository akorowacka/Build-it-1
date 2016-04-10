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
     * Fetch user data with current id
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
     * Create a User from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     500 = "Returned when the form has errors"
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
     * @RequestParam(name="address", nullable=false, description="Adress")
     *
     * @return View
     */

    public function postUsersAction(ParamFetcher $paramFetcher) {
        $user = new User();

        $user->setName($paramFetcher->get('name'));
        $user->setSurname($paramFetcher->get('surname'));
        $user->setEmail($paramFetcher->get('email'));
        $user->setPassword($paramFetcher->get('password'));
        $user->setAddress($paramFetcher->get('address'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view('User added with id ' .$user->getId(), 200);;
    }

    /**
     * Update a current User data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Update a current User data",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user wasn't found"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="id", nullable=true, description="User Id")
     * @RequestParam(name="name", nullable=true, description="Name")
     * @RequestParam(name="surname", nullable=true, description="Surname")
     * @RequestParam(name="email", nullable=true, description="Email")
     * @RequestParam(name="password", nullable=true, description="Password")
     * @RequestParam(name="avatar_url", nullable=true, description="Avatar url")
     *
     * @return View
     */
    public function putUsersAction(ParamFetcher $paramFetcher) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($paramFetcher->get('id'));

        if (!$user) {
          throw $this->createNotFoundException(
            'No user with' .$paramFetcher->get('id'). ' id found.'
          );
        }

        if($paramFetcher->get('name')){ $user->setName($paramFetcher->get('name')); }
        if($paramFetcher->get('surname')){ $user->setSurname($paramFetcher->get('surname')); }
        if($paramFetcher->get('avatar_url')){ $user->setName($paramFetcher->get('avatar_url')); }
        if($paramFetcher->get('password')){ $user->setName($paramFetcher->get('password')); }

        $em->flush();

        return $this->view('User updated with id ' .$paramFetcher->get('id'), 200);;
    }

    /**
     * Delete user with provided Id
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete user with provided Id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user wasn't found"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="id", nullable=true, description="User Id")
     * @RequestParam(name="name", nullable=true, description="Name")
     * @RequestParam(name="surname", nullable=true, description="Surname")
     * @RequestParam(name="email", nullable=true, description="Email")
     * @RequestParam(name="password", nullable=true, description="Password")
     * @RequestParam(name="avatar_url", nullable=true, description="Avatar url")
     *
     * @return View
     */
    public function deleteUsersAction(ParamFetcher $paramFetcher) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($paramFetcher->get('id'));

        if (!$user) {
          throw $this->createNotFoundException(
            'No user with id ' .$paramFetcher->get('id'). ' found.'
          );
        }

        $em->remove($user);
        $em->flush();

        return $this->view('Deleted user with id ' .$paramFetcher->get('id'), 200);;
    }

}
