<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Photo;



class PhotoController extends FOSRestController
{
  
  /**
	* @Rest\Get("/photos/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($id);

		if ($singleresult === null) {
			$response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Photos Not Found."
      ]));
      $response->setStatusCode(404);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
		}
		return $singleresult;
	}


  /**
  * @Rest\Get("/photos/institution/{id}")
  */
  public function getByInstIdAction($id)
  {

    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Photo')
      ->findBy(
        array('instId' => intval($id)),
        array('id' => 'DESC')
        );
    
    if ($results === null) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Photos Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    return $results;
  }

  /**
  * @Rest\Get("/photos/user/{id}")
  */
  public function getByUsrIdAction()
  {
    
     $results = $this->getDoctrine()
      ->getRepository('AppBundle:Photo')
      ->findBy(array('usrId' => intval($id)));
    
    if ($result === null) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Photos Not Found."
      ]));
      $response->setStatusCode(404);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    return $result;
  }


/**
 * @Rest\Post("/api/photos")
 */
  public function postAction(Request $request) {
    $data = new Photo;
    $usrId = $request->get('usrId');
    $instId = $request->get('instId');
    $priority = $request->get('priority');
    $src = $request->get('src');
    $msrc = $request->get('msrc');
    $sqr = $request->get('sqr');
    $w = $request->get('w');
    $h = $request->get('h');

    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
   

    if (empty($src) || empty($msrc) || empty($w) || empty($h)) {
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

    if ($usrId) {
      if ($user->getId() !== intval($usrId)) {
        $response = new Response();
        $response->setContent(json_encode([
            'error' => true,
            'code' => 401,
            'message' => "Unathorized"
        ]));
        $response->setStatusCode(401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      }

      $data->setUsrId($usrId);
    }

    if ($instId) {
      $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($instId);

      if ($user->getId() !== $institution->getOwner()) {
        $response = new Response();
        $response->setContent(json_encode([
            'error' => true,
            'code' => 401,
            'message' => "Unathorized"
        ]));
        $response->setStatusCode(401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      }

      $data->setInstId($instId);
    }

    if ($priority) {
      $data->setPriority($priority);
    }

    if ($sqr) {
      $data->setSqr($sqr);
    }

    $data->setSrc($src);
    $data->setMsrc($msrc);
    $data->setW($w);
    $data->setH($h);

    $em = $this->getDoctrine()->getManager();
    $em->persist($data);
    $em->flush();

    return $data;
  }


/**
 * @Rest\Put("/api/photos/{id}")
 */
 public function updateAction($id,Request $request) { 
  $data = new Photo;
  $usrId = $request->get('usrId');
  $instId = $request->get('instId');
  $priority = $request->get('priority');
  $src = $request->get('src');
  $msrc = $request->get('msrc');
  $sqr = $request->get('sqr');
  $w = $request->get('w');
  $h = $request->get('h');
  $sn = $this->getDoctrine()->getManager();

  $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($id);

  if ($src) {
    $photo->setSrc($src);
  }

  if ($msrc) {
    $photo->setMsrc($msrc);
  }

  if ($sqr) {
    $data->setSqr($sqr);
  }

  if ($w) {
     $photo->setW($w);
  }

  if ($h) {
     $photo->setH($h);
  }

  if (!empty($priority)) {
    $photo->setPriority($priority);
  }

  $sn->flush();
  
  return $photo;
}


/**
 * @Rest\Delete("/api/photos/{id}")
 */
 public function deleteAction($id)
 {
  $data = new Photo;
  $sn = $this->getDoctrine()->getManager();
  $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($id);
if (empty($photo)) {
  return new View("photo not found", Response::HTTP_NOT_FOUND);
 }
 else {
  $sn->remove($photo);
  $sn->flush();
 }
  return new View("deleted successfully", Response::HTTP_OK);
 }


}
