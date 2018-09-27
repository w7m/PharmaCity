<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Media;
use AppBundle\Entity\Patient;
use AppBundle\Form\PatientType;
use AppBundle\Form\MediaType;
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
        return new response("<h1>opppppppppp</h1>");

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
     * @Route("/editmedia", name="edit_media_patient")
     * @Security("has_role('ROLE_USER')")
     */

    public function editMediaAction(Request $request)
    {
        $user = $this->getUser();
        $media = $user->getMedia();
        if ($media){
            $form = $this->createForm(MediaType::class, $media);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
//                 $form->getData();
                $oldPath = $media->getPath();
                $file = $form["file"]->getData();
                $fileName = md5(uniqid()).".".$file->guessExtension();
                $media->setPath("upload/picture_user/".$fileName);
                $media->setAlt("user");
//                dump($media);
//                die();
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                unlink(__DIR__."/../../../"."web/".$oldPath);
                $file->move($this->getParameter('user_media'),$fileName);
                $request->getSession()->getFlashBag()->add('notice', 'Image a bien été modifiée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Patient/add_media_patient.html.twig', array(
                'form' => $form->createView(),
            ));

        } else {
            $media = new Media();
            $form = $this->createForm(MediaType::class, $media);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $media->setUser($user);
                $em->persist($media);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Image a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Patient/add_media_patient.html.twig', array(
                'form' => $form->createView(),
            ));
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
            $prescription = $patient->getPrescriptions();
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
                $prescription->setStatus("En attente");
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Pharmacie ajoutée avec succès ');
                return $this->redirectToRoute('list_prescription_patient');
            }
            return $this->render('@App/Prescription/add_prescription.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/mylistprescriptionmedication/{id}", name="my_list_prescription_medicacation")
     * @Security("has_role('ROLE_USER')")
     */
    public function listPrescriptionMedication(Prescription $prescription)
    {
        $user = $this->getUser();
        $patient = $prescription->getPatient();
        $userPatient = $patient->getUser();
        if ($user === $userPatient) {
            $prescriptionMedication = $prescription->getPrescriptionMedication();
            return $this->render('@App/Prescription/list_prescription_medication_patient.html.twig',array('prescriptionMedication'=>$prescriptionMedication));
        } else{
            return $this->redirectToRoute('homepage');
        }
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
