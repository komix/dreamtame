<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Suggestion;
use Symfony\Component\EventDispatcher\EventDispatcher;


class SuggestionController extends FOSRestController
{

  /**
  * @Rest\Get("/suggestions")
  */
  public function getAction()
  {
    $suggestions = $this->getDoctrine()->getRepository('AppBundle:Suggestion')->findAll();

    if (empty($suggestions)) {
      return array();
    } else {
      return $suggestions;
    }
    
  }

  /**
	* @Rest\Get("/suggestions/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:Suggestion')->find($id);
		if ($singleresult === null) {
			return new View("suggestion not found", Response::HTTP_NOT_FOUND);
		}
		return $singleresult;
	}

/**
 * @Rest\Post("/api/suggestions")
 */
  public function postAction(Request $request) 
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

    $suggestion = new Suggestion;

    $name = $request->get('name');
    $imgUrl = $request->get('imgUrl');
    $url = $request->get('url');
    $isActive = $request->get('isActive');

    $response = new Response();

    if (empty($name) || empty($imgUrl) || empty($url)) {
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

    $suggestion->setName($name);
    $suggestion->setImgUrl($imgUrl);
    $suggestion->setUrl($url);

    if (!empty($isActive)) {
      $suggestion->setIsActive($isActive);
    }
    

    $em = $this->getDoctrine()->getManager();
    $em->persist($suggestion);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Suggestion was created successfully!",
          'id' => $suggestion->getId()
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');
   
    return $response;
  }


   /**
   * @Rest\Put("/api/suggestions/{id}")
   */
   public function updateAction($id,Request $request) { 
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


    $suggestion = new Suggestion;
    $name = $request->get('name');
    $imgUrl = $request->get('imgUrl');
    $url = $request->get('url');
  
    $isActive = $request->get('isActive');

    $response = new Response();

    $sn = $this->getDoctrine()->getManager();
    $suggestion = $this->getDoctrine()->getRepository('AppBundle:Suggestion')->find($id);

    if (empty($name) || empty($imgUrl))  {
      $response
        ->setContent(json_encode([
          'error' => true,
          'code' => 400,
          'message' => "Bad Request"
        ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    } else {

      $suggestion->setName($name);
      $suggestion->setImgUrl($imgUrl);
      $suggestion->setUrl($url);
      
      if (!empty($isActive)) {
        $suggestion->setIsActive($isActive);
      }

      $sn->flush();
    
      $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Suggestion was updated successfully!"
        ]));
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

  }

  /**
  * @Rest\Delete("/api/suggestions/{id}")
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

      $category = new Suggestion;
      $sn = $this->getDoctrine()->getManager();
      $category = $this->getDoctrine()->getRepository('AppBundle:Suggestion')->find($id);
      $response = new Response();

      if (empty($suggestion)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 404,
            'message' => "Suggestion Not Found."
        ]));
        $response->setStatusCode(400);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } else {
        $sn->remove($suggestion);
        $sn->flush();

        $response
        ->setContent(json_encode([
            'success' => true,
            'code' => 200,
            'message' => "Suggestion was deleted successfully!"
          ]));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
       
        return $response;
      }
    }


}