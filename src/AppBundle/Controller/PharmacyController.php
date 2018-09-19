<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pharmacy;
use AppBundle\Form\PharmacyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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


}
