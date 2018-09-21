<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Prescription;
use AppBundle\Form\PrescriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/prescription")
 */
class PrescriptionController extends Controller
{
    /**
     * @Route("/add", name="add_prescription")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function addAction(Request $request)
    {
            $prescription = new Prescription();
            $form = $this->createForm(PrescriptionType::class, $prescription);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($prescription);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Prescription/add_prescription.html.twig', array(
                'form' => $form->createView(),
            ));
    }
}
