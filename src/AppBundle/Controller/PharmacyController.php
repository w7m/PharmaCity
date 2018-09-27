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
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/listprescription/{status}", name="pharmacy_prescriptions_by_status")
     * @Security("has_role('ROLE_PHARMACY')")
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

            $pharmacy = $this->getDoctrine()->getRepository(Pharmacy::class)->findOneBy(["user" => $userId]);
        if ($pharmacy) {
            $prescriptions = $this->getDoctrine()->getRepository(Prescription::class)->findBy([
                'patient' => $pharmacy,
                'status' => $status
            ]);
            return $this->render('@App/Prescription/list_prescription_doctor.html.twig', array(
                'prescription' => $prescriptions,
            ));
        } else {
            return $this->redirectToRoute('add_info_pharmacy');
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
        if ($pharmacy){
        $prescriptions = $pharmacy->getPrescription();
        return $this->render('@App/Prescription/list_prescription_pharmacy.html.twig',array('prescription'=>$prescriptions));

        } else {
            return $this->redirectToRoute('add_info_pharmacy');
        }
    }


    /**
     * @Route("/listprescriptionmedication/{id}", name="list_priscription_medication_pharmacy")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function listPrescriptionMedicationPharmacyAction(Prescription $prescription)
    {
        $prescriptionMedication = $prescription->getPrescriptionMedication();
        return $this->render('@App/Prescription/list_prescription_medication_pharmacy.html.twig',array('prescriptionMedication'=>$prescriptionMedication));
    }


    /**
     * @Route("/setpriceprescriptinmedication/{id}/{price}",
     *      name="set_price_priscription_medication_pharmacy",
     *      requirements={"id"="\d+","price"="\d+"},
     *      options={"expose"="true"})
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function setPricePrescriptionMedicationAction($id,$price, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $prescriptionMedication = $em->getRepository('AppBundle:PrescriptionMedication')->find($id);
        $prescriptionMedication->setPrice($price);
        $em->flush();
        $priceNew = array("price"=>$price);
        return new JsonResponse($priceNew);
    }

    /**
     * @Route("/confirmationprescriptin/{id}/",
     *      name="confirmation_priscription_medication_pharmacy",
     *      requirements={"id"="\d+"},
     *      options={"expose"="true"})
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function confirmationPrescriptionAction(Prescription $prescription, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $prescriptionMedications = $prescription->getPrescriptionMedication();
        foreach ($prescriptionMedications as $prescriptionMedication){
            if ($prescriptionMedication->getPrice() == null){
                $priceEmpty = array("status"=>"Prix vide !");
                return new JsonResponse($priceEmpty);
            } else {
                $prescription->setStatus("Confirmée");
                $em->flush();
                $priceNew = array("status"=>"Confirmé");
                return new JsonResponse($priceNew);
            }
        }

    }
    /**
     * @return mixed
     * @Route("/searchprescritions", name="pharmacy_search_query")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function searchAction(Request $request) {

        $term = $request->query->get('term');
        $userId = $this->getUser()->getId();


        $repository = $this->getDoctrine()->getRepository(Prescription::class);
        $pharmacy = $this->getDoctrine()->getRepository(Pharmacy::class)->findOneBy(["user" => $userId]);
        $pharmacyId = $pharmacy->getId();
        $prescriptions = $repository->searchPrescriptions($term, 'pharmacy', $pharmacyId);

        return $this->render('@App/Prescription/search.html.twig',array(
            'prescriptions'=>$prescriptions
        ));
    }

    /**
     * @return mixed
     * @Route("/cancelprescription/{id}", name="cancel_prescription")
     * @Security("has_role('ROLE_PHARMACY')")
     */
    public function cancelDemandeAction(Request $request, Prescription $prescription)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $pharmacy = $em->getRepository('AppBundle:Pharmacy')->findOneBy(array('user'=>$user));
        if ($pharmacy)
        {
            $pharmacyPrescription =$prescription->getPharmacy();
            if ($pharmacyPrescription === $pharmacy){
                $prescription->setStatus("Annulée");
              $prescriptionMedication = $prescription->getPrescriptionMedication();
                foreach ($prescriptionMedication as $prescriptionmed){
                    $prescriptionmed->setPrice(0);
                }
                $em->flush();
                return $this->redirectToRoute('list_priscription_pharmacy');
            } else {
                return $this->redirectToRoute('list_priscription_pharmacy');
            }
        } else {
            return $this->redirectToRoute('list_priscription_pharmacy');
        }
    }


}
