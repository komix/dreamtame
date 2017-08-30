<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Video;



class VideoController extends FOSRestController
{
  
  /**
	* @Rest\Get("/videos/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:Video')->find($id);

		if ($singleresult === null) {
			$response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Video Not Found."
      ]));
      $response->setStatusCode(404);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
		}
		return $singleresult;
	}


  /**
  * @Rest\Get("/videos/institution/{id}")
  */
  public function getByInstIdAction($id)
  {

    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Video')
      ->findBy(
        array(
          'instance' => 'institution',
          'instanceId' => intval($id)
          ),
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

  // /**
  // * @Rest\Get("/photos/user/{id}")
  // */
  // public function getByUsrIdAction()
  // {
    
  //    $results = $this->getDoctrine()
  //     ->getRepository('AppBundle:Photo')
  //     ->findBy(array('usrId' => intval($id)));
    
  //   if ($result === null) {
  //     $response = new Response();
  //     $response->setContent(json_encode([
  //         'error' => true,
  //         'code' => 404,
  //         'message' => "Photos Not Found."
  //     ]));
  //     $response->setStatusCode(404);
  //     $response->headers->set('Content-Type', 'application/json');

  //     return $response;
  //   }

  //   return $result;
  // }


/**
 * @Rest\Post("/api/videos")
 */
  public function postAction(Request $request) {
    $data = new Video;
    $imgUrl = $request->get('imgUrl');
    $instance = $request->get('instance');
    $instanceId = $request->get('instanceId');
    $ytbId = $request->get('ytbId');
    $ytbUrl = $request->get('ytbUrl');
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
   

    if ((empty($ytbId) && empty($ytbUrl)) || empty($instance) || empty($instanceId) || empty($imgUrl)) {
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

    // if ($usrId) {
    //   if ($user->getId() !== intval($usrId)) {
    //     $response = new Response();
    //     $response->setContent(json_encode([
    //         'error' => true,
    //         'code' => 401,
    //         'message' => "Unathorized"
    //     ]));
    //     $response->setStatusCode(401);
    //     $response->headers->set('Content-Type', 'application/json');

    //     return $response;
    //   }

    //   $data->setUsrId($usrId);
    // }

    if ($instance === 'institution') {
      $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($instanceId);
    } 



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

    $data->setInstanceId($instanceId);

    if (!empty($ytbUrl)) {
      $data->setYtbUrl($ytbUrl);
    }

    if (!empty($ytbId)) {
      $data->setYtbId($ytbId);
    }

    $data->setInstanceId($instanceId);
    $data->setInstance($instance);
    $data->setImgUrl($imgUrl);
  

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
  $imgUrl = $request->get('imgUrl');
  $instance = $request->get('instance');
  $instanceId = $request->get('instanceId');
  $ytbId = $request->get('ytbId');
  $ytbUrl = $request->get('ytbUrl');

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
 * @Rest\Delete("/api/videos/{id}")
 */
 public function deleteAction($id)
 {
  $sn = $this->getDoctrine()->getManager();
  $video = $this->getDoctrine()->getRepository('AppBundle:Video')->find($id);
  if (empty($video)) {
    return new View("video not found", Response::HTTP_NOT_FOUND);
  } else {
    $sn->remove($video);
    $sn->flush();
  }
  return new View("deleted successfully", Response::HTTP_OK);
 }


}
