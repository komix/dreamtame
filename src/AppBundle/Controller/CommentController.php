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
use AppBundle\Entity\Comment;

class CommentController extends FOSRestController
{
  /**
  * @Rest\Post("/comments/institution/{id}")
  */
  public function getByInstIdAction($id, Request $request)
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
      ->getRepository('AppBundle:Comment')
      ->findBy(
        array('institutionId' => $id,
              'commentId' => null),
        array('id' => 'DESC'),
        $limit,
        $offset 
        );
    
    if ($results === null) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Comments Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    return $results;
  }


  /**
  * @Rest\Post("/comments/articles/{id}")
  */
  public function getByArticleIdAction($id, Request $request)
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
      ->getRepository('AppBundle:Comment')
      ->findBy(
        array('articleId' => $id,
              'commentId' => null),
        array('id' => 'DESC'),
        $limit,
        $offset 
        );
    
    if ($results === null) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Comments Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    return $results;
  }


  /**
  * @Rest\Post("/api/comments")
  */
  public function postAction(Request $request) {
    $comment = new Comment;
    $text = $request->get('text');
    $institutionId = $request->get('institutionId');
    $articleId = $request->get('articleId');
    $commentId = $request->get('commentId');
   
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
   
    if (empty($text)) {
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
    
    if (!empty($institutionId)) {
      $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($institutionId);
      $comment->setInstitution($institution);
    }

    if (!empty($articleId)) {
      $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
      $comment->setArticle($article);
    }

    $dateUtc = new \DateTime(null, new \DateTimeZone("UTC"));

    $comment->setText($text);
    $comment->setAuthor($user);
    $comment->setCreatedAt($dateUtc);

    if (!empty($commentId)) {
      $original = $this->getDoctrine()->getRepository('AppBundle:Comment')->find($commentId);
      $comment->setOriginal($original);
    }

    $em = $this->getDoctrine()->getManager();
    $em->persist($comment);
    $em->flush();

    return $comment;
  }

/**
 * @Rest\Delete("/api/phone-numbers/{id}")
 */
 public function deleteAction($id)
 {
  $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->find($id);

  if (empty($comment)) {
     $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "Not found."
      ]));
      $response->setStatusCode(404);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
  } 

  $token = $this->get('security.token_storage')->getToken();
  $user = $token->getUser();

  if ($user->getId() !== $comment->getAuthor()) {
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
  $sn->remove($comment);
  $sn->flush();

  return $comment; 
 }


}
