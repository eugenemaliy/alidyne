<?php

namespace Alydine\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Alydine\FrontBundle\Entity\Image;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$pictures = $em->getRepository("AlydineFrontBundle:Image")->findAll();
        return $this->render('AlydineFrontBundle:Default:index.html.twig', array(
    			"pictures" => $pictures,
    		));
    }

    public function registerAction()
    {
        return $this->redirect($this->generateUrl('alydine_front_homepage'));
    }

    public function adminAction()
    {	
    	$em = $this->getDoctrine()->getManager();
    	$pictures = $em->getRepository("AlydineFrontBundle:Image")->findAll();
    	return $this->render('AlydineFrontBundle:Admin:admin.html.twig', array(
    			"pictures" => $pictures,
    		));
    }

    public function uploadPictureAction()
    {
    	ini_set('memory_limit','-1');
		$format = $this->getRequest()->getRequestFormat();
		$em = $this->getDoctrine()->getManager();
		$request = $this->get('request');
		$image = $request->files->get('_image');

        $error = NULL;

		if ($request->getMethod() != 'POST') {
			$error['status'] = 'error';
			$error['message'] = 'The request is not in POST Method.';
		}

		else if (empty($image)) {
			$error['status'] = 'error';
			$error['message'] = 'Missing parameters. The picture cannot be null.';
		}

        else if (!empty($image) && $image->getSize() > 10485760) {
            $error['status'] = 'error';
            $error['message'] = 'Le poids de la photo est supérieur à 10 Mo.';
        }

        else if (!empty($image) && $image->guessExtension() != 'jpeg' && $image->guessExtension() != 'png') {
            $error['status'] = 'error';
            $error['message'] = 'La photo doit être au format jpeg ou png.';
        }

		if (empty($error)) {
			$tmp = new Image();
			$tmp->file = $image;
			$tmp->setName();
			$tmp->upload();

			$em->persist($tmp);
			$em->flush();
			return new Response($this->get('jms_serializer')->serialize($tmp, 'json'));
		} else {
			return new Response($this->get('jms_serializer')->serialize($error, 'json'));
		}
    }

    public function removePictureAction()
    {
		$em = $this->getDoctrine()->getManager();
		$request = $this->get('request');
		$image = $request->get('_image_name');

		if ($request->getMethod() != 'POST') {
			$error['status'] = 'error';
			$error['message'] = 'The request is not in POST Method.';
			return new Response($this->get('jms_serializer')->serialize($error, $format));
		}

		$picture = $em->getRepository("AlydineFrontBundle:Image")->findOneByName($image);
		$em->remove($picture);
		$em->flush();

		$message['code'] = "OK";
		$message['message'] = "Picture successfuly deleted";

		return new Response($this->get('jms_serializer')->serialize($message, 'json'));

    }
}
