<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Classs;
use AppBundle\Form\ClasssType;


class ClasssController extends Controller
{
    /**
     * @Route("/classs/create", name="add_class")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        // $classes = $em->getRepository('AppBundle:Classs')
        // 	->findBy(
        // 		array('user' => $user),
        // 		array('id' => 'DESC')
        // 	);

        $classs = new Classs();
        $classs->setUser($user);

        $form = $this->createForm(ClasssType::class, $classs);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($classs);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Classs created successfully!'
        	);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_class'
                : 'homepage';

            return $this->redirectToRoute($nextAction);
		} 

	
        // replace this example code with whatever you need
        return $this->render('class/create.html.twig',['form' => $form->createView()] );


    }

    /**
     * @Route("/classs/list", name="list_classes")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user' => $user),
                array('classNumber' => 'ASC')
            );

        $data['classes'] = $classes;

        return $this->render('class/list.html.twig', $data );

    }

    /**
     * @Route("/classs/edit/{classsId}", name="edit_classs")
     */
    public function editAction(Request $request, $classsId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classsId);


        $form = $this->createForm(ClasssType::class, $classs);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Classs edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_class'
                : 'list_classes';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['class_number'] = $classs->getClassNumber();
            $form_data['class_wing'] = $classs->getClassWing();
            $form_data['class_teacher'] = $classs->getClassTeacher();
            $data['form'] = $form_data;
        }
        $data['classs'] = $classs;


        return $this->render('class/edit.html.twig', ['form' => $form->createView(), $data,] );

    }

    /**
     * @Route("/classs/delete/{classsId}", name="delete_classs")
     */
    public function deleteAction(Request $request, $classsId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classsId);

        $em->remove($classs);
        $em->flush();

        return $this->redirectToRoute('list_classes');

    }


}