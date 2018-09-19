<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Doctor;
use AppBundle\Form\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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


}
