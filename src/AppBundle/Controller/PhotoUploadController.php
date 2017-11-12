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


class PhotoUploadController extends FOSRestController
{
    /**
    * @Rest\Post("/image-upload")
    */
    public function postAction(Request $request) {

        ini_set("memory_limit", "-1");
        $base64Image = $request->get('base64Image');
        $instance = $request->get('instance');
        $instanceId = $request->get('instanceId');
        $data = base64_decode(explode(",", $base64Image)[1]);
        $formImage = imagecreatefromstring($data);       
        $name = uniqid('image', true) . '.jpeg';


        if (!file_exists('uploads/' . $instance)) {
          mkdir('uploads/' . $instance, 0777, true);
        }

        if (!empty($instance) && !empty($instanceId)) {

            if (!file_exists('uploads/' . $instance)) {
              mkdir('uploads/' . $instance, 0777, true);
            }

            if (!file_exists('uploads/' . $instance . '/' . $instanceId)) {
              mkdir('uploads/' . $instance . '/' . $instanceId, 0777, true);
            }

            $directory = 'uploads/' . $instance . '/' . $instanceId . '/' . $name;
        } else {
            $directory = 'uploads/' . $name;
        }

        $image = imagejpeg($formImage, $directory);

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        if ($instance && $instanceId) {
            $imgUrl = $baseurl . '/uploads/' . $instance . '/' . $instanceId . '/' . $name;

        } else {
            $imgUrl = $baseurl . '/uploads/' . $name;
        }

        $size = getimagesize($directory);
    
        $width = $size[0];
        $height = $size[1];
        $resp = new \stdClass();
        $resp->src = $imgUrl;
        $resp->width = $width;
        $resp->height = $height;

        return new Response(json_encode($resp));;
    }
}