<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Form\PatientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\PrescriptionPatientType;
use AppBundle\Entity\Prescription;

/**
 * @Route("/patient", name="")
 */
class PatientController extends Controller
{

    /**
     * @Route("/info", name="info_patient")
     */
    public function indexAction(Request $request)
    {
        return new response("<h1>opppppppppp/h1>");

    }
    /**
     * @Route("/addinfo", name="add_info_patient")
     * @Security("has_role('ROLE_USER')")
     */

    public function addInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository('AppBundle:Patient')->findByUser($user);
        if ($patient)
        {
            return $this->redirectToRoute('edit_info_patient');
        } else {
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

    /**
     * @Route("/editinfo", name="edit_info_patient")
     * @Security("has_role('ROLE_USER')")
     */
    public function editInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository('AppBundle:Patient')->findOneBy(array('user'=>$user));
        if ($patient)
        {
            $form = $this->createForm(PatientType::class, $patient);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $patient->setUser($this->getUser());
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Patient/add_patient.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('edit_add_patient');
        }

    }

    /**
     * @Route("/mylistprescription/{status}", name="patient_prescriptions_by_status")
     * @Security("has_role('ROLE_USER')")
     */
    public function getPrescriptionsActionByState(Request $request, $status) {
        $userId = $this->getUser()->getId();
        switch ($status) {
            case 'pending':
                $status = 'Non confirmé';
                break;
            case 'ongoing':
                $status = 'Confirmé';
                break;
            case 'canceled':
                $status = 'Annulée';
                break;
            case 'delivred':
                $status = 'Livrée';
                break;
            case 'success':
                $status = 'Confirmé';
                break;
        }
        $patient = $this->getDoctrine()->getRepository(Patient::class)->findOneBy(["user" => $userId]);
        if ($patient){
            $prescriptions = $this->getDoctrine()->getRepository(Prescription::class)->findBy([
                'patient' => $patient,
                'status' => $status
            ]);
            return $this->render('@App/Prescription/list_prescription_patient.html.twig', array(
                'prescription' => $prescriptions,
            ));
        } else {
            return $this->redirectToRoute('add_info_patient');
        }
    }


    /**
     * @Route("/mylistprescription", name="list_prescription_patient")
     * @Security("has_role('ROLE_USER')")
     */

    public function listPrescriptionPatientAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $patient = $em->getRepository('AppBundle:Patient')->findOneBy(array('user'=>$user));
        if ($patient)
        {
            $prescription = $patient->getPrescription();
            return $this->render('@App/Prescription/list_prescription_patient.html.twig',array('prescription'=>$prescription));
        } else {
            return $this->redirectToRoute('add_info_patient');
        }

    }
    /**
     * @Route("/choicepharmacy/{id}", name="add_pharmacy_to_prescription")
     * @Security("has_role('ROLE_USER')")
     */
    public function choicePharmacyPacientAction(Request $request, Prescription $prescription)
    {
        $patient = $prescription->getPatient();
        $user = $patient->getUser();
        $userCurrent = $this->getUser();
        if ($user === $userCurrent){
            $form = $this->createForm(PrescriptionPatientType::class, $prescription);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Prescription/add_prescription.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('info_patient');
        }

    }

    /**
     * @Route("/mylistprescriptionmedication/{id}", name="my_list_prescription_medicacation")
     * @Security("has_role('ROLE_USER')")
     */
    public function listPrescriptionMedication(Prescription $prescription)
    {
        $prescriptionMedication = $prescription->getPrescriptionMedication();
        return $this->render('@App/Prescription/list_prescription_medication_patient.html.twig',array('prescriptionMedication'=>$prescriptionMedication));
    }

    /**
     * @return mixed
     * @Route("/searchprescritions", name="patient_search_query")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchAction(Request $request) {

        $term = $request->query->get('term');
        $userId = $this->getUser()->getId();


        $repository = $this->getDoctrine()->getRepository(Prescription::class);
        $patient = $this->getDoctrine()->getRepository(Patient::class)->findOneBy(["user" => $userId]);
        $patientId = $patient->getId();
        $prescriptions = $repository->searchPrescriptions($term, 'patient', $patientId);

        return $this->render('@App/Prescription/search.html.twig',array(
            'prescriptions'=>$prescriptions
        ));
    }

}
