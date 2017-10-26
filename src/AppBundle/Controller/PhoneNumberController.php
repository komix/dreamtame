<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Institution;
use AppBundle\Entity\PhoneNumber;

class PhoneNumberController extends FOSRestController
{
  
  /**
  * @Rest\Post("/api/phone-numbers")
  */
  public function postAction(Request $request) {
    $data = new PhoneNumber;
    $rawNumber = $request->get('rawNumber');
    $institutionId = $request->get('institutionId');
   
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
   
    if (empty($rawNumber) || empty($institutionId)) {
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
    
    $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($institutionId);

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

    $data->setRawNumber($rawNumber);
    $data->setInstitution($institution);

    $em = $this->getDoctrine()->getManager();
    $em->persist($data);
    $em->flush();

    return $data;
  }


/**
 * @Rest\Put("/api/phone-numbers/{id}")
 */
 public function updateAction($id,Request $request) { 
    $phoneNumber = $this->getDoctrine()->getRepository('AppBundle:PhoneNumber')->find($id);
    $rawNumber = $request->get('rawNumber');
    $institutionId = $request->get('institutionId');
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();

    if (empty($rawNumber) || empty($institutionId)) {
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

    $institution = $phoneNumber->getInstitution();

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
    
   
    $em = $this->getDoctrine()->getManager();

    $phoneNumber->setRawNumber($rawNumber);
    
    $em->persist($phoneNumber);
    $em->flush();

    return $phoneNumber;  
}


/**
 * @Rest\Delete("/api/phone-numbers/{id}")
 */
 public function deleteAction($id)
 {
  $phoneNumber = $this->getDoctrine()->getRepository('AppBundle:PhoneNumber')->find($id);

  if (empty($phoneNumber)) {
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

  $token = $this->get('security.token_storage')->getToken();
  $user = $token->getUser();

  $institution = $phoneNumber->getInstitution();

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

  $sn = $this->getDoctrine()->getManager();
  $sn->remove($phoneNumber);
  $sn->flush();

  return $phoneNumber; 
 }


}
