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
use AppBundle\Entity\WorkingHours;
use AppBundle\Entity\WorkingDays;



class WorkingHoursController extends FOSRestController
{
  
  /**
	* @Rest\Get("/working-hours/{id}")
	*/
	public function idAction($id)
	{
		$singleresult = $this->getDoctrine()->getRepository('AppBundle:WorkingHours')->find($id);

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
  * @Rest\Get("/working-hours/institution/{id}")
  */
  public function getByInstIdAction($id)
  {

    $results = $this->getDoctrine()
      ->getRepository('AppBundle:WorkingHours')
      ->findBy(
        array('institutionId' => $id),
        array('id' => 'ASC')
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
 * @Rest\Post("/api/working-hours")
 */
  public function postAction(Request $request) {
    $data = new WorkingHours;
    $name = $request->get('name');
    $workingDays = $request->get('workingDays');
    $institutionId = $request->get('institutionId');
    $isDefaultSchedule = $request->get('isDefaultSchedule');
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();
   
    if (empty($name) || empty($institutionId) || empty($workingDays)) {
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

    $data->setName($name);
    $data->setInstitution($institution);
    $data->setIsDefaultSchedule($isDefaultSchedule);

    $em = $this->getDoctrine()->getManager();
    $em->persist($data);

    foreach ($workingDays as $day) {
        $workingDay = new WorkingDays;
        $workingDay->setDayNumber($day['dayNumber']);
        $workingDay->setStart(new \DateTime($day['start']));
        $workingDay->setEnd(new \DateTime($day['end']));
        $workingDay->setWorkingHours($data);
        $data->addWorkingDay($workingDay);
    }
    
    $em->persist($data);
    $em->flush();

    return $data;
  }


/**
 * @Rest\Put("/api/working-hours/{id}")
 */
 public function updateAction($id,Request $request) { 
    $workingHours = $this->getDoctrine()->getRepository('AppBundle:WorkingHours')->find($id);
    $name = $request->get('name');
    $workingDays = $request->get('workingDays');
    $institutionId = $request->get('institutionId');
    
    $token = $this->get('security.token_storage')->getToken();
    $user = $token->getUser();

    if (empty($name) || empty($institutionId) || empty($workingDays)) {
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

    $institution = $workingHours->getInstitution();

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
    
    $oldWorkingDays = $workingHours->getWorkingDays();
    $em = $this->getDoctrine()->getManager();

    foreach ($oldWorkingDays as $day) {
      $workingHours->removeWorkingDay($day);
      $em->remove($day);
    }

    foreach ($workingDays as $day) {
        $workingDay = new WorkingDays;
        $workingDay->setDayNumber($day['dayNumber']);
        $workingDay->setStart(new \DateTime($day['start']));
        $workingDay->setEnd(new \DateTime($day['end']));
        $workingDay->setWorkingHours($workingHours);
        $workingHours->addWorkingDay($workingDay);
    }

    $workingHours->setName($name);
    
    $em->persist($workingHours);
    $em->flush();

    return $workingHours;  
}


/**
 * @Rest\Delete("/api/working-hours/{id}")
 */
 public function deleteAction($id)
 {
  $sn = $this->getDoctrine()->getManager();
  $workingHours = $this->getDoctrine()->getRepository('AppBundle:WorkingHours')->find($id);

  if (empty($workingHours)) {
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

  $institution = $workingHours->getInstitution();

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

  $workingDays = $workingHours->getWorkingDays();

  foreach ($workingDays as $day) {
    $workingHours->removeWorkingDay($day);
    $sn->remove($day);
  }

  $sn->remove($workingHours);

  $sn->flush();

  return $workingHours; 
  
  return new View("deleted successfully", Response::HTTP_OK);
 }


}
