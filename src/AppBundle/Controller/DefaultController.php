<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/index", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('@App/Default/index.html.twig');

    }
}
