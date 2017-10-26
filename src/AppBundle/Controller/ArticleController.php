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
use AppBundle\Entity\Article;

class ArticleController extends FOSRestController
{

  /**
  * @Rest\Post("/news")
  */
  public function getAction(Request $request)
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
      ->getRepository('AppBundle:Article')
      ->findBy(
        array(),
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
   * @Rest\Get("/news/{id}")
   */
  public function idAction($id)
  {
    $singleresult = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

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
 * @Rest\Post("/api/news")
 */
  public function postAction(Request $request) 
  {
    $article = new Article;

    $title = $request->get('title');
    $snippet = $request->get('snippet');
    $text = $request->get('text');
    $imgUrl = $request->get('imgUrl');
    $createdAt = $request->get('createdAt');
    
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

    if (empty($title) || empty($text) || empty($imgUrl)) {
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

    if (!empty($createdAt)) {
      $article->setCreatedAt(new \DateTime($createdAt));
    }

    $article->setTitle($title);
    $article->setSnippet($snippet);
    $article->setText($text);
    $article->setImgUrl($imgUrl);
    $article->setAuthor($user);

    $em = $this->getDoctrine()->getManager();
    $em->persist($article);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Article was created successfully!",
          'articleId' => $article->getId()
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }


  /**
   * @Rest\Put("/api/news/{id}")
   */
   public function updateAction($id, Request $request) {
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
    $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);

    if (empty($article)) {
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
    
    $title = $request->get('title');
    $snippet = $request->get('snippet');
    $text = $request->get('text');
    $imgUrl = $request->get('imgUrl');
    $createdAt = $request->get('createdAt');

    if (!empty($title)) {
      $article->setTitle($title);
    }

    if (!empty($snippet)) {
      $article->setSnippet($snippet);
    }

    if (!empty($text)) {
      $article->setText($text);
    }

    if (!empty($imgUrl)) {
      $article->setImgUrl($imgUrl);
    }

    if (!empty($createdAt)) {
      $article->setCreatedAt(new \DateTime($createdAt));
    }

    $sn = $this->getDoctrine()->getManager();
    // $sn->persist($article);
    $sn->flush();

    return $article;
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
