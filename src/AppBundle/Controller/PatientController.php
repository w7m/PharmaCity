<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/patient", name="patient")
 */
class PatientController extends Controller
{
    /**
     * @Route("/index", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/Default/index.html.twig');

    }
}
