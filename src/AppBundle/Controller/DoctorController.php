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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
                $prescription->setStatus("Non confirmée");
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
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $prescription = $session->get('prescription');

        $reference = $prescription->getReference();
        $prescriptionMedicationAdded = $em->getRepository('AppBundle:PrescriptionMedication')->findBy(array('prescription'=>$prescription));
        $prescriptionMedication = new PrescriptionMedication();
        $form = $this->createForm(PrescriptionMedicationType::class,$prescriptionMedication ,['reference'=>$reference]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($prescriptionMedication);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
            return $this->redirectToRoute('add_prescription_medication');
        }
        return $this->render('@App/Prescription/add_prescription_medication.html.twig', array(
            'form' => $form->createView(),
            'prescriptionMedicationAdded'=>$prescriptionMedicationAdded
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
                $status = 'Non confirmée';
                break;
            case 'ongoing':
                $status = 'Confirmée';
                break;
            case 'canceled':
                $status = 'Annulée';
                break;
            case 'delivred':
                $status = 'En attente';
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
//      $session = $request->getSession();
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

    /**
     * @Route("/finmedication/{medic}", name="find_medication",
     *     options={"expose"="true"})
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function findMedicationDoctor(Request $request, $medic)
    {
        if ($medic=="all"){
            $em = $this->getDoctrine()->getManager();
            $medications1 = $em->getRepository('AppBundle:Medication')->findAll();
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $json = $serializer->serialize($medications1, 'json');
        } else {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Medication');
            $medications = $repository->getMedicationByname($medic);
            if($medications) {
                $encoders = array(new XmlEncoder(), new JsonEncoder());
                $normalizers = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizers, $encoders);
                $json = $serializer->serialize($medications, 'json');
            } else {
                $em = $this->getDoctrine()->getManager();
                $medications1 = $em->getRepository('AppBundle:Medication')->findAll();
                $encoders = array(new XmlEncoder(), new JsonEncoder());
                $normalizers = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizers, $encoders);
                $json = $serializer->serialize($medications1, 'json');
            }
        }
        $response = new JsonResponse();
        $response->setData(array('jsonMedication'=>$json));
       return $response;
    }

    /**
     * @Route("/deleteprescription/{id}", name="delete_prescription")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function deletePrescriptionAction(Request $request, Prescription $prescription)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('AppBundle:Doctor')->findOneBy(array('user'=>$user));
        if ($doctor)
        {
            $doctorPrescription =$prescription->getDoctor();
            if ($doctorPrescription === $doctor){
                $em->remove($prescription);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été supprimée.');
                return $this->redirectToRoute('list_prescription_doctor');
            } else {
                return $this->redirectToRoute('list_prescription_doctor');
            }


        } else {
            return $this->redirectToRoute('list_prescription_doctor');
        }
    }


    /**
     * @return mixed
     * @Route("/searchprescritions", name="doctor_search_query")
     * @Security("has_role('ROLE_DOCTOR')")
     */
    public function searchAction(Request $request) {

        $term = $request->query->get('term');
        $userId = $this->getUser()->getId();
        $repository = $this->getDoctrine()->getRepository(Prescription::class);
        $doctor = $this->getDoctrine()->getRepository(Doctor::class)->findOneBy(["user" => $userId]);
        $doctorId = $doctor->getId();
        $prescriptions = $repository->searchPrescriptions($term, 'doctor', $doctorId);

        return $this->render('@App/Prescription/search.html.twig',array(
            'prescriptions'=>$prescriptions
        ));
    }





}
