<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use AppBundle\Entity\EmailConfirmTokens;
use Symfony\Component\EventDispatcher\EventDispatcher;

class UserController extends FOSRestController
{
  /**
    * @Rest\Get("/users")
    */
  public function getAction()
  {
    $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
    if ($restresult === null) {
      return new View("there are no users exist", Response::HTTP_NOT_FOUND);
    }
    return $restresult;
  }


  /**
	* @Rest\Get("/users/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
    $response = new Response();

		if ($singleresult === null) {
			$response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "User Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
      
      return response;
		} else {
      $user = new \stdClass;

      $user->id = $singleresult->getId();
      $user->firstName = $singleresult->getFirstName();
      $user->lastName = $singleresult->getLastName();
      $user->photoId = $singleresult->getPhotoId();
      $user->email = $singleresult->getEmail();
      $user->smallPhotoUrl = $singleresult->getSmallPhotoUrl();

      $response->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'data' => $user
      ]));
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

		// return $singleresult;
	}

  

  /**
  * @Rest\Get("/api/users/get/by-token")
  */
  public function getUserByToken(Request $request) 
   {
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();

    return $user;
   }



/**
 * @Rest\Post("/users")
 */
  public function postAction(Request $request, \Swift_Mailer $mailer) 
  {
    $username = $request->get('username');
    $userEmail = $request->get('email');
    $userPlainPassword = $request->get('plainPassword');

    $response = new Response();

    if (!$userEmail || !$userPlainPassword) {
      $response->setContent(json_encode([
          'error' => true,
          'code' => 400,
          'message' => "Bad Request"
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

    $userManager = $this->container->get('fos_user.user_manager');
    $existingUser = $userManager->findUserBy(array('email' => $userEmail));

    if ($existingUser) {
      $response->setContent(json_encode([
          'error' => true,
          'code' => 500,
          'duplicate' => true,
          'message' => "user already exists"
      ]));

      $response->setStatusCode(500);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }
    
    $user = $userManager->createUser();
    $user->setUsername($username);
    $user->setEmail($userEmail);
    $user->setPlainPassword($userPlainPassword);
    
    $userManager->updateUser($user);

    $random = md5(uniqid($user->getEmail(), true));

    $confirmToken = new EmailConfirmTokens;
    $confirmToken->setUserId($user->getId());
    $confirmToken->setToken($random);

    $em = $this->getDoctrine()->getManager();
    $em->persist($confirmToken);
    $em->flush();

    $message = \Swift_Message::newInstance()
    ->setSubject('Email Confirmation')
    ->setFrom('dreamtame@gmail.com')
    ->setTo($user->getEmail())
    ->setBody(
          $this->renderView(
              'Emails/confirmation.html.twig',
              array('url' => $confirmToken->getToken())
            ),
            'text/html'
      );

    $this->get('mailer')
      ->send($message);

    $response->setContent(json_encode([
        'success' => true,
        'code' => 200,
        'message' => "user created successfully"
    ]));

    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  /**
 * @Rest\Post("/activate-user/{token}")
 */
  public function activateUser(Request $request) 
  {
    $token = $request->get('token');

    $response = new Response();

    $singleresult = $this->getDoctrine()
      ->getRepository('AppBundle:EmailConfirmTokens')
      ->findBy(array('token' => $token));

    if ($singleresult === null) {
       $response->setContent(json_encode([
          'error' => true,
          'code' => 500,
          'message' => "token expired or already used"
      ]));

      $response->setStatusCode(500);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

    $userId = $singleresult[0]->getUserId();

    $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($userId);

    if (!$user) {
      $response->setContent(json_encode([
          'error' => true,
          'code' => 500,
          'message' => "user not found"
      ]));

      $response->setStatusCode(500);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

    $user->setEnabled(1);

    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();

    return $user;
  }

  /**
 * @Rest\Put("/api/users/{id}")
 */
 public function updateAction($id, Request $request) 
 {

  $token = $this->get('security.token_storage')->getToken();
  $user = $token->getUser();
  $requestedUserId = intval($id);

  if (($user->getId() !== $requestedUserId) && !$user->hasRole('ROLE_ADMIN')) {
    $response = new Response();

    $response
      ->setContent(json_encode([
        'error' => true,
        'code' => 401,
        'message' => "No permission"
      ]));

    $response->setStatusCode(500);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  $data = new User;
  $firstName = $request->get('firstName');
  $lastName = $request->get('lastName');
  // $email = $request->get('email');
  $photoId = $request->get('photoId');
  $sn = $this->getDoctrine()->getManager();
  $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

  if (!empty($firstName)) {
    $user->setFirstName($firstName);
  }

  if (!empty($lastName)) {
    $user->setLastName($lastName);
  }

  if (!empty($email)) {
    $user->setEmail($email);
  }

  if (!empty($photoId)) {
    $user->setPhotoId($photoId);
    $photo = $this->getDoctrine()->getRepository('AppBundle:Photo')->find($photoId);

    if ($photo->getSqr()) {
      $photoUrl = $photo->getSqr();
    } else {
      $photoUrl = $photo->getMsrc();
    }

    $user->setSmallPhotoUrl($photoUrl);
  }

  $sn->flush();

  return $user;
}

  /**
 * @Rest\Delete("api/users/{id}")
 */
public function deleteAction($id)
 {
    $data = new User;
    $sn = $this->getDoctrine()->getManager();
    $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

    $response = new Response();

    if (empty($user)) {
        $response->setContent(json_encode([
            'error' => true,
            'code' => 500,
            'message' => "user not found"
        ]));

        $response->setStatusCode(404);
        $response->headers->set('Content-Type', 'application/json');
       
        return $response;
    } else {
        $sn->remove($user);
        $sn->flush();

        $response->setContent(json_encode([
            'success' => true,
            'code' => 200,
            'message' => "user not found"
        ]));

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
    }

    return false;
  }

}