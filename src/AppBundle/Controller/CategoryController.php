<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Category;
use Symfony\Component\EventDispatcher\EventDispatcher;


class CategoryController extends FOSRestController
{
  /**
    * @Rest\Get("/categories/get-tree")
    */
  public function getTree()
  {
    $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

    function getSourceCategory($categoriesList) {
      for ($i = 0; $i < count($categoriesList); $i++) {
        if ($categoriesList[$i]->getParent() === 0) {
          return $categoriesList[$i];
        }
      }
    }

    function getCategoryChildren($category, $allCategories) {
      $children = array();

      for ($i = 0; $i < count($allCategories); $i++) {
        if ($allCategories[$i]->getParent() === $category->getId()) {
          array_push($children, $allCategories[$i]);
        }
      }

      if (count($children) > 0) {
        return $children;
      } else {
        return false;
      }
    }

    function formTree($categoriesList, $allCategories) {
      for ($i = 0; $i < count($categoriesList); $i++) {
        $children = getCategoryChildren($categoriesList[$i], $allCategories);
        
        if ($children) {
          $categoriesList[$i]->setChildren($children);
          formTree($children, $allCategories);
        }
      }

      return $categoriesList;
    }

    if ($categories === null) {
      $response = new Response();
      $response->setContent(json_encode([
          'error' => true,
          'code' => 404,
          'message' => "User Not Found."
      ]));
      $response->setStatusCode(400);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    } else {
      $sourceCat = getSourceCategory($categories);
      $baseCategories = getCategoryChildren($sourceCat, $categories);
      $tree = formTree($baseCategories, $categories);

      return $tree;
    }
    
  }


  /**
	* @Rest\Get("/categories/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
		if ($singleresult === null) {
			return new View("user not found", Response::HTTP_NOT_FOUND);
		}
		return $singleresult;
	}


/**
 * @Rest\Post("/api/categories")
 */
  public function postAction(Request $request) 
  {
    $category = new Category;
    $name = $request->get('name');
    $parent = $request->get('parent');
    $ukName = $request->get('ukName');

    $response = new Response();

    if (empty($name) || empty($parent) || empty($ukName)) {
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

    $category->setName($name);
    $category->setParent($parent);
    $category->setUkName($ukName);

    $em = $this->getDoctrine()->getManager();
    $em->persist($category);
    $em->flush();

    $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Category was created successfully!"
        ]));
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/json');
   
    return $response;
  }


   /**
   * @Rest\Put("/api/categories/{id}")
   */
   public function updateAction($id,Request $request) { 
    $category = new Category;
    $name = $request->get('name');
    $parent = $request->get('parent');
    $ukName = $request->get('ukName');

    $response = new Response();

    $sn = $this->getDoctrine()->getManager();
    $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);

    if (empty($name) || empty($parent) || empty($ukName))  {
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
      $category->setName($name);
      $category->setParent($parent);
      $category->setUkName($ukName);

      $sn->flush();
    
      $response
      ->setContent(json_encode([
          'success' => true,
          'code' => 200,
          'message' => "Category was edited successfully!"
        ]));
      $response->setStatusCode(200);
      $response->headers->set('Content-Type', 'application/json');
     
      return $response;
    }

  }

  /**
  * @Rest\Delete("/api/categories/{id}")
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