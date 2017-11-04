<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Area;
use AppBundle\Entity\User;

class AreaController extends FOSRestController
{

 /**
 * @Rest\Post("/api/areas")
 */
  public function postAction(Request $request) 
  {
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

    $area = new Area;

    $name = $request->get('name');
    $fromLat = $request->get('fromLat');
    $fromLng = $request->get('fromLng');
    $toLat = $request->get('toLat');
    $toLng = $request->get('toLng');
    
    $response = new Response();

    if (empty($name) || empty($fromLat) || empty($fromLng) || empty($toLat) || empty($toLng)) {
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

    $area->setName($name);
    $area->setFromLat($fromLat);
    $area->setFromLng($fromLng);
    $area->setToLat($toLat);
    $area->setToLng($toLng);

    $em = $this->getDoctrine()->getManager();
    $em->persist($area);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Location was added successfully!",
          'articleId' => $area->getId()
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  /**
  * @Rest\Delete("/api/areas/{id}")
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
      $area = $this->getDoctrine()->getRepository('AppBundle:Area')->find($id);
      $response = new Response();

      if (empty($area)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 404,
            'message' => "Area Not Found."
        ]));
        $response->setStatusCode(400);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } 

      $sn->remove($area);
      $sn->flush();

      $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Area was deleted successfully!"
        ]));
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    
    }


  /**
  * @Rest\Post("/areas/find/by-point")
  */
  public function search(Request $request)
  {

    $point = $request->get('point');

    if (empty($point)) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 400,
          'message' => "Bad request."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();

    $q = $qb->select(array('a'))
       ->from('AppBundle:Area', 'a')
       ->where(
         $qb->expr()->lt('a.fromLat', $point['lat']),
         $qb->expr()->gt('a.toLat', $point['lat']),
         $qb->expr()->lt('a.fromLng', $point['lng']),
         $qb->expr()->gt('a.tolng', $point['lng'])
       )
       ->setMaxResults(1)
       ->getQuery()
       ->getOneOrNullResult();

    $area = $q->getResult();

    if (empty($area)) {
      return array();
    } else {
      return $area;
    }
  }


}
