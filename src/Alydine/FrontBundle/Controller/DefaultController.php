<?php

namespace Alydine\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AlydineFrontBundle:Default:index.html.twig');
    }
}
