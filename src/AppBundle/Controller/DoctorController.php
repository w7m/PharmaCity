<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Doctor;
use AppBundle\Form\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\PrescriptionMedicationType;
use AppBundle\Form\PrescriptionType;
use AppBundle\Entity\PrescriptionMedication;
use AppBundle\Entity\Prescription;

/**
 * @Route("/doctor", name="")
 */
class DoctorController extends Controller
{

    /**
     * @Route("/info", name="info_doctor")
     */
    public function indexAction(Request $request)
    {
        return new response("<h1>fzzzzz</h1>");

    }
    /**
     * @Route("/addinfo", name="add_info_doctor")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function addInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('AppBundle:Doctor')->findByUser($user);
        if ($doctor)
        {
            return $this->redirectToRoute('edit_info_doctor');
        } else {
            $doctor = new Doctor();
            $form = $this->createForm(DoctorType::class, $doctor);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $doctor->setUser($this->getUser());
                $em->persist($doctor);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Doctor/add_doctor.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/editinfo", name="edit_info_doctor")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function editInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('AppBundle:Doctor')->findOneBy(array('user'=>$user));
        if ($doctor)
        {
            $form = $this->createForm(DoctorType::class, $doctor);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $doctor->setUser($this->getUser());
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Doctor/add_doctor.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('edit_add_doctor');
        }

    }

    /**
     * @Route("/addprescription", name="add_prescription")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function addPrescriptionDoctorAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('AppBundle:Doctor')->findOneBy(array('user'=>$user));
        if ($doctor)
        {
            $session = $request->getSession();
            $prescription = new Prescription();
            $prescription->setDoctor($doctor);
            $form = $this->createForm(PrescriptionType::class, $prescription);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $prescription->setDoctor($doctor);
                $session->set('prescription',$prescription);
                $em->persist($prescription);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('add_prescription_medication');
            }
            return $this->render('@App/Prescription/add_prescription.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('add_info_doctor');
        }

    }

    /**
     * @Route("/addmedication", name="add_prescription_medication")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function addPrescriptionMedicationDoctorAction(Request $request)
    {
        $session = $request->getSession();
        $prescription = $session->get('prescription');
        $reference = $prescription->getReference();
        $prescriptionMedication = new PrescriptionMedication();
        $form = $this->createForm(PrescriptionMedicationType::class,$prescriptionMedication ,['reference'=>$reference]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prescriptionMedication);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
            return $this->redirectToRoute('add_prescription_medication');
        }
        return $this->render('@App/Prescription/add_prescription_medication.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/listprescription/{status}", name="doctor_prescriptions_by_status")
     * @Security("has_role('ROLE_DOCTOR')")
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
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->findOneBy(["user" => $userId]);
        if ($doctor){
            $prescriptions = $this->getDoctrine()->getRepository(Prescription::class)->findBy([
                'patient' => $doctor,
                'status' => $status
            ]);
            return $this->render('@App/Prescription/list_prescription_doctor.html.twig', array(
                'prescription' => $prescriptions,
            ));
        } else {
            return $this->redirectToRoute('add_info_doctor');
        }
    }



    /**
     * @Route("/listprescription", name="list_prescription_doctor")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function  listPrescriptionForDocotrAction(Request $request)
    {
//        $session = $request->getSession();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('AppBundle:Doctor')->findOneBy(array('user'=>$user));
        if ($doctor)
        {
            $listPrescription = $doctor->getPrescription();
            return $this->render('@App/Prescription/list_prescription_doctor.html.twig',array('prescription'=>$listPrescription));
        } else {
            return $this->redirectToRoute('add_info_doctor');
        }

    }

    /**
     * @Route("/listprescriptionmedication/{id}", name="list_priscription_medication_doctor")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function prescriptionDetailedForDoctor(Request $request, Prescription $prescription)
    {
        $prescriptionMedication = $prescription->getPrescriptionMedication();
        return $this->render('@App/Prescription/list_prescription_medication_doctor.html.twig',array('prescriptionMedication'=>$prescriptionMedication));

    }





}
