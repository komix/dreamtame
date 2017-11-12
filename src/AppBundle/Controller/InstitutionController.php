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
use AppBundle\Entity\Area;


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
  * @Rest\Post("/institutions/category/{id}")
  */
  public function getByCategoryIdAction($id, Request $request)
  {
    $offset = $request->get('offset');
    $limit = $request->get('limit');

    if (empty($offset)) {
      $offset = 0;
    }

    if (empty($limit)) {
      $limit = 1000;
    }

    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->findBy(
        array('categoryId' => intval($id)),
        array('id' => 'DESC'),
        $limit,
        $offset 
        );

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
   * @Rest\Post("/institutions/last-n")
   */
  public function getLastNInstances(Request $request)
  {
    $limit = $request->get('limit');

    if (empty($limit)) {
      $limit = 5;
    }

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();

    $q = $qb->select(array('i'))
         ->from('AppBundle:Institution', 'i')
         ->where('i.imgUrl IS NOT NULL')
         ->orderBy('i.id', 'DESC')
         ->setMaxResults($limit)
         ->getQuery();

    $institutions = $q->getResult();

    if (empty($institutions)) {
      return array();
    } else {
      return $institutions;
    }
  }



  /**
   * @Rest\Post("/institutions/last")
   */
  public function getLastByIdsListAction(Request $request)
  {
    $limit = $request->get('limit');
    $idsList = $request->get('idsList');

    if (empty($limit)) {
      $limit = 5;
    }

    $results = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->findBy(
        array('categoryId' => $idsList),
        array('id' => 'DESC'),
        $limit
        );

    if (empty($results)) {
      return array();
    } else {
      return $results;
    }
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

      $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

      if ($photo->getSqr()) {
        $photoUrl = $photo->getSqr();
      } else {
        $photoUrl = $photo->getMsrc();
      }

      $institution->setImgUrl($photoUrl);
    }

    if (!empty($lat) && !empty($lng)) {
      $institution->setLat($lat);
      $institution->setLng($lng);

      $em = $this->getDoctrine()->getManager();
      $qb = $em->createQueryBuilder();

      $area = $qb->select(array('a'))
       ->from('AppBundle:Area', 'a')
       ->where(
         $qb->expr()->lt('a.fromLat', $lat),
         $qb->expr()->gt('a.toLat', $lat),
         $qb->expr()->lt('a.fromLng', $lng),
         $qb->expr()->gt('a.toLng', $lng)
       )
       ->setMaxResults(1)
       ->getQuery()
       ->getOneOrNullResult();

       if (!empty($area)) {
          $institution->setLocationId($area->getLocationId());
       }
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
      $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

      if ($photo->getSqr()) {
        $photoUrl = $photo->getSqr();
      } else {
        $photoUrl = $photo->getMsrc();
      }

      $institution->setImgUrl($photoUrl);
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
  * @Rest\Delete("/api/institutions/{id}")
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
      $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($id);
      $response = new Response();

      if (empty($institution)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 404,
            'message' => "User Not Found."
        ]));
        $response->setStatusCode(400);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } 

      $sn->remove($institution);
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


  /**
   * @Rest\Post("/api/institutions/{id}/recruit-age")
   */
  public function addRecruitAge($id, Request $request)
  {
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($id);

    $recruitFrom = $request->get('recruitFrom');
    $recruitTo = $request->get('recruitTo');

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

    if (empty($recruitFrom) || empty($recruitTo)) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 400,
          'message' => "Bad request."
      ]));
      $response->setStatusCode(401);
      $response->headers->set('Content-Type', 'application/json');
    }

    $institution->setRecruitFrom($recruitFrom);
    $institution->setRecruitTo($recruitTo);

    $sn = $this->getDoctrine()->getManager();
    $sn->flush();

    $response = new Response();
    $response->setContent(json_encode([
        'success' => true,
        'code' => 200,
        'message' => "Recruit age updated successfully."
    ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  /**
  * @Rest\Post("/institutions-search")
  */
  public function search(Request $request)
  {

    $point = $request->get('point');
    $radius = $request->get('radius');
    $limit = $request->get('limit');
    $offset = $request->get('offset');
    $categoriesIds = $request->get('categoriesIds');

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

    if (empty($offset)) {
      $offset = 0;
    }

    if (empty($limit)) {
      $limit = 0;
    }

    $r_earth = 6378;
    $pi = pi();
    $fromLat  = $point['lat'] - ($radius / $r_earth) * (180 / $pi);
    $toLat  = $point['lat'] + ($radius / $r_earth) * (180 / $pi);
    $fromLng = $point['lng'] - ($radius / $r_earth) * (180 / $pi) / cos($point['lat'] * $pi/180);
    $toLng = $point['lng'] + ($radius / $r_earth) * (180 / $pi) / cos($point['lat'] * $pi/180);

    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();

    if (empty($categoriesIds)) {
       $q = $qb->select(array('i'))
           ->from('AppBundle:Institution', 'i')
           ->where(
             $qb->expr()->lt('i.lat', $toLat),
             $qb->expr()->gt('i.lat', $fromLat),
             $qb->expr()->lt('i.lng', $toLng),
             $qb->expr()->gt('i.lng', $fromLng)
           )
           ->orderBy('i.id', 'ASC')
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->getQuery();
    } else {
      $q = $qb->select(array('i'))
           ->from('AppBundle:Institution', 'i')
           ->where(
             $qb->expr()->lt('i.lat', $toLat),
             $qb->expr()->gt('i.lat', $fromLat),
             $qb->expr()->lt('i.lng', $toLng),
             $qb->expr()->gt('i.lng', $fromLng)
           )
           ->andWhere("i.categoryId IN(:categoriesIds)")
           ->setParameter('categoriesIds', $categoriesIds)
           ->orderBy('i.id', 'ASC')
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->getQuery();
    }

    

    $institutions = $q->getResult();

    if (empty($institutions)) {
      return array();
    } else {
      return $institutions;
    }
  }

}