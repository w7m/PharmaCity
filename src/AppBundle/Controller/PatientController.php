<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Form\PatientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/patient", name="")
 */
class PatientController extends Controller
{

    /**
     * @Route("/info", name="info")
     */
    public function indexAction(Request $request)
    {
        return new response("<h1>fzzzzz</h1>");

    }
    /**
     * @Route("/addinfo", name="add_info")
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function addInfoAction(Request $request)
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $patient->setUser($this->getUser());
            $em->persist($patient);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('@App/Patient/add_patient.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
