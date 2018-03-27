<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ExamCompany;
use AppBundle\Form\ExamCompanyType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExamCompanyController extends Controller
{
    /**
     * @Route("/examCompany/create", name="add_company")
     */
    public function createAction(Request $request)
    {
    	$data = [];
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	$em = $this->getDoctrine()->getManager();

        $examCompany = new ExamCompany();
        $examCompany->setUser($user);

        $form = $this->createForm(ExamCompanyType::class, $examCompany);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($examCompany);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'ExamCompany created successfully!'
        	);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_company'
                : 'homepage';

            return $this->redirectToRoute($nextAction);

		} 

	
        // replace this example code with whatever you need
        return $this->render('examCompany/create.html.twig',['form' => $form->createView()] );

    }


    /**
     * @Route("/exam_company/list", name="list_exam_companies")
     */
    public function listCompaniesAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('AppBundle:ExamCompany')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $data['companies'] = $companies;

        return $this->render('examCompany/list.html.twig', $data );

    }

    /**
     * @Route("/exam_company/edit/{companyId}", name="edit_exam_company")
     */
    public function editCompanyAction(Request $request, $companyId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('AppBundle:ExamCompany')
            ->find($companyId);


        $form = $this->createForm(ExamCompanyType::class, $company);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Company edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_company'
                : 'list_exam_companies';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['company_name'] = $company->getCompanyName();
            $data['form'] = $form_data;
        }
        $data['company'] = $company;


        return $this->render('examCompany/edit.html.twig', ['form' => $form->createView(), $data,] );

    }

    /**
     * @Route("/exam_company/delete/{companyId}", name="delete_exam_company")
     */
    public function deleteAction(Request $request, $companyId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('AppBundle:ExamCompany')
            ->find($companyId);

        $em->remove($company);
        $em->flush();

        return $this->redirectToRoute('list_exam_companies');

    }


}