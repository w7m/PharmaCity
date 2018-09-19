<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Supplier;
use AppBundle\Form\SupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/supplier", name="")
 */
class SupplierController extends Controller
{

    /**
     * @Route("/info", name="info_supplier")
     */
    public function indexAction(Request $request)
    {
        return new response("<h1>fzzzzz</h1>");

    }
    /**
     * @Route("/addinfo", name="add_info_supplier")
     * @Security("has_role('ROLE_SUPPLIER')")
     */
    public function addInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository('AppBundle:Supplier')->findByUser($user);
        if ($supplier)
        {
            return $this->redirectToRoute('edit_info_supplier');
        } else {
            $supplier = new Supplier();
            $form = $this->createForm(SupplierType::class, $supplier);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $supplier->setUser($this->getUser());
                $em->persist($supplier);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Supplier/add_supplier.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/editinfo", name="edit_info_supplier")
     * @Security("has_role('ROLE_SUPPLIER)")
     */
    public function editInfoAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository('AppBundle:Supplier')->findOneBy(array('user'=>$user));
        if ($supplier)
        {
            $form = $this->createForm(SupplierType::class, $supplier);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $supplier->setUser($this->getUser());
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Information a bien été ajoutée.');
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/Supplier/add_supplier.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            return $this->redirectToRoute('edit_add_supplier');
        }

    }


}
