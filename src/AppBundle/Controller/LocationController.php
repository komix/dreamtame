<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Location;
use AppBundle\Entity\User;

class LocationController extends FOSRestController
{

  /**
  * @Rest\Get("/")
  */
  public function getAction()
  {
    $locations = $this->getDoctrine()->getRepository('AppBundle:Location')->findAll();

    if (empty($locations)) {
      return array();
    } else {
      return $locations;
    } 
  }

  /**
   * @Rest\Get("/locations/{id}")
   */
  public function idAction($id)
  {
    $singleresult = $this->getDoctrine()->getRepository('AppBundle:Location')->find($id);

    if (empty($singleresult)) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "User Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }
    return $singleresult;
  }

 /**
 * @Rest\Post("/api/locations")
 */
  public function postAction(Request $request) 
  {
    $location = new Location;

    $name = $request->get('name');
    $lat = $request->get('lat');
    $lng = $request->get('lng');
    
    $response = new Response();

    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $ownerId = $user->getId();


    if (!$user->hasRole('ROLE_ADMIN')) {
      $response->setContent(json_encode([
          'error' => true,
          'code' => 401,
          'message' => "No permission."
      ]));
      $response->setStatusCode(401);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    if (empty($name) || empty($lat) || empty($lng)) {
      $response
        ->setContent(json_encode([
          'error' => true,
          'code' => 400,
          'message' => "Bad Request"
        ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

    $location->setName($name);
    $location->setLat($lat);
    $location->setLng($lng);

    $em = $this->getDoctrine()->getManager();
    $em->persist($location);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Location was added successfully!",
          'articleId' => $locaton->getId()
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }


  /**
   * @Rest\Put("/api/locations/{id}")
   */
   public function updateAction($id, Request $request) {
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $location = $this->getDoctrine()->getRepository('AppBundle:Location')->find($id);

    if (empty($location)) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Article Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    if (!$user->hasRole('ROLE_ADMIN')) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 401,
          'message' => "No permission."
      ]));
      $response->setStatusCode(401);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }
    
    $name = $request->get('name');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    if (!empty($name)) {
      $location->setName($name);
    }

    if (!empty($lat)) {
      $location->setLat($lat);
    }

    if (!empty($lng)) {
      $location->setLng($lng);
    }

    $sn = $this->getDoctrine()->getManager();
    // $sn->persist($article);
    $sn->flush();

    return $location;
  }

  /**
  * @Rest\Delete("/api/locations/{id}")
  */
  public function deleteAction($id)
    {
      $token = $this->get('security.token_storage')->getToken();
      $user = $token->getUser();

      if (!$user->hasRole('ROLE_ADMIN')) {
        $response = new Response();
        $response->setContent(json_encode([
            'error' => true,
            'code' => 401,
            'message' => "No permission."
        ]));
        $response->setStatusCode(401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      }

      $sn = $this->getDoctrine()->getManager();
      $location = $this->getDoctrine()->getRepository('AppBundle:Location')->find($id);
      $response = new Response();

      if (empty($location)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 404,
            'message' => "Location Not Found."
        ]));
        $response->setStatusCode(400);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } 

      $sn->remove($location);
      $sn->flush();

      $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Location was deleted successfully!"
        ]));
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    
    }


}
