<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pharmacy;
use AppBundle\Form\PharmacyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Prescription;

/**
 * @Route("/pharmacy", name="")
 */
class PharmacyController extends Controller
{

    /**
     * @Route("/info", name="info_pharmacy")
     */
    public function indexAction(Request $request)
    {
        return new response("<h1>fzzzzz</h1>");

    }
    /**
     * @Route("/addinfo", name="add_info_pharmacy")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function addInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $pharmacy = $em->getRepository('AppBundle:Pharmacy')->findByUser($user);
        if ($pharmacy)
        {
            return $this->redirectToRoute('edit_info_pharmacy');
        } else {
            $pharmacy = new Pharmacy();
            $form = $this->createForm(PharmacyType::class, $pharmacy);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $pharmacy->setUser($this->getUser());
                $em->persist($pharmacy);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Pharmacy/add_pharmacy.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/editinfo", name="edit_info_pharmacy")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function editInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $pharmacy = $em->getRepository('AppBundle:Pharmacy')->findOneBy(array('user'=>$user));
        if ($pharmacy)
        {
            $form = $this->createForm(PharmacyType::class, $pharmacy);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $pharmacy->setUser($this->getUser());
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Pharmacy/add_pharmacy.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('edit_add_pharmacy');
        }

    }

    /**
     * @Route("/listprescription", name="list_priscription_pharmacy")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function listPrescriptionPharmacyAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $pharmacy = $em->getRepository('AppBundle:Pharmacy')->findOneBy(array('user'=>$user));
        $prescriptions = $pharmacy->getPrescription();
        return $this->render('@App/Prescription/list_prescription_pharmacy.html.twig',array('prescription'=>$prescriptions));
    }

    /**
     * @Route("/listprescriptionmedication/{id}", name="list_priscription_medication_pharmacy")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function listPrescriptionMedicationPharmacyAction(Prescription $prescription)
    {
        $prescriptionMedication = $prescription->getPrescriptionMedication();
//         $em = $this->getDoctrine()->getManager();
//         $prescriptionMedication =  $em->getRepository('AppBundle:PrescriptionMedication')->findOneBy(array('prescription'=>$prescription));
        return $this->render('@App/Prescription/list_prescription_medication_pharmacy.html.twig',array('prescriptionMedication'=>$prescriptionMedication));
    }


}
