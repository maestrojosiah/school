<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Subject;
use AppBundle\Form\SubjectType;


class SubjectController extends Controller
{
    /**
     * @Route("/subject/create", name="add_subject")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        // $subjects = $em->getRepository('AppBundle:Subject')
        // 	->findBy(
        // 		array('user' => $user),
        // 		array('id' => 'DESC')
        // 	);

        $subject = new Subject();
        $subject->setUser($user);

        $form = $this->createForm(SubjectType::class, $subject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($subject);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Subject created successfully!'
        	);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_subject'
                : 'homepage';

            return $this->redirectToRoute($nextAction);

		} 

	
        // replace this example code with whatever you need
        return $this->render('subject/create.html.twig',['form' => $form->createView()] );

    }

    /**
     * @Route("/subject/list", name="list_subjects")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $subjects = $em->getRepository('AppBundle:Subject')
            ->findBy(
                array('user' => $user),
                array('subjectTitle' => 'ASC')
            );

        $data['subjects'] = $subjects;

        return $this->render('subject/list.html.twig', $data );

    }

    /**
     * @Route("/subject/edit/{subjectId}", name="edit_subject")
     */
    public function editAction(Request $request, $subjectId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $subject = $em->getRepository('AppBundle:Subject')
            ->find($subjectId);


        $form = $this->createForm(SubjectType::class, $subject);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Subject edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_subject'
                : 'list_subjects';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['subject_title'] = $subject->getSubjectTitle();
            $form_data['subject_code'] = $subject->getSubjectCode();
            $data['form'] = $form_data;
        }
        $data['subject'] = $subject;


        return $this->render('subject/edit.html.twig', ['form' => $form->createView(), $data,] );

    }

    /**
     * @Route("/subject/delete/{subjectId}", name="delete_subject")
     */
    public function deleteAction(Request $request, $subjectId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $subject = $em->getRepository('AppBundle:Subject')
            ->find($subjectId);

        $em->remove($subject);
        $em->flush();

        return $this->redirectToRoute('list_subjects');

    }

}