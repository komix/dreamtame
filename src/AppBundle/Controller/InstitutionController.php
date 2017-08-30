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
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcher;


class InstitutionController extends FOSRestController
{
  /**
    * @Rest\Get("/institutions")
    */
  public function getAction()
  {
    $institutions = $this->getDoctrine()->getRepository('AppBundle:Institution')->findAll();

    if (empty($institutions)) {
      return array();
    } else {
      return $institutions;
    }
    
  }

  /**
  * @Rest\Get("/institutions/category/{id}")
  */
  public function getByCategoryIdAction($id)
  {
    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->findBy(array('categoryId' => intval($id)));

    if (empty($results)) {
      return array();
    } else {
      return $results;
    }
  }


  /**
   * @Rest\Get("/institutions/owner/{id}")
   */
  public function getByOwnerIdAction($id)
  {
    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->findBy(array('owner' => intval($id)));

    if (empty($results)) {
      return array();
    } else {
      return $results;
    }
  }


  /**
	 * @Rest\Get("/institutions/{id}")
	 */
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($id);

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
 * @Rest\Post("/api/institutions")
 */
  public function postAction(Request $request) 
  {
    $institution = new Institution;

    $title = $request->get('title');
    $categoryId = $request->get('categoryId');
    $description = $request->get('description');
    $photoId = $request->get('photoId');
    $lat = $request->get('lat');
    $lng = $request->get('lng');
    $address = $request->get('address');

    $response = new Response();

    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $ownerId = $user->getId();

    if (empty($title) || empty($ownerId) || empty($categoryId)) {
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

    $institution->setTitle($title);
    $institution->setOwner($ownerId);
    $institution->setCategoryId($categoryId);
    $institution->setIsActivated(false);

    if (!empty($description)) {
      $institution->setDescription($description);
    }

    if (!empty($photoId)) {
      $institution->setPhotoId($photoId);
    }

    if (!empty($lat) && !empty($lng)) {
      $institution->setLat($lat);
      $institution->setLng($lng);
    }

    if (!empty($address)) {
      $institution->setAddress($address);
    }

    $em = $this->getDoctrine()->getManager();
    $em->persist($institution);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Institution was created successfully!",
          'institutionId' => $institution->getId()
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }


   /**
   * @Rest\Put("/api/institutions/{id}")
   */
   public function updateAction($id, Request $request) {
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($id);

    if (empty($institution)) {
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

    if (($user->getId() !== $institution->getOwner()) && !$user->hasRole('ROLE_ADMIN')) {
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
    
    $title = $request->get('title');
    $categoryId = $request->get('categoryId');
    $description = $request->get('description');
    $photoId = $request->get('photoId');
    $address = $request->get('address');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    if (!empty($title)) {
      $institution->setTitle($title);
    }

    if (!empty($categoryId)) {
      $institution->setCategoryId($categoryId);
    }

    if (!empty($description)) {
      $institution->setDescription($description);
    }

    if (!empty($photoId)) {
      $institution->setPhotoId($photoId);
    }

    if (!empty($address)) {
      $institution->setAddress($address);
    }

    if (!empty($lat) && !empty($lng)) {
      $institution->setLat($lat);
      $institution->setLng($lng);
    }

    $response = new Response();

    $sn = $this->getDoctrine()->getManager();
    $sn->flush();

    return $institution;

  }

  /**
  * @Rest\Delete("/categories/{id}")
  */
  public function deleteAction($id)
    {
      $category = new Category;
      $sn = $this->getDoctrine()->getManager();
      $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
      $response = new Response();

      if (empty($category)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 404,
            'message' => "User Not Found."
        ]));
        $response->setStatusCode(400);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } else {
        $sn->remove($category);
        $sn->flush();

        $response
        ->setContent(json_encode([
            'success' => true,
            'code' => 200,
            'message' => "Category was deleted successfully!"
          ]));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
       
        return $response;
      }
    }


}