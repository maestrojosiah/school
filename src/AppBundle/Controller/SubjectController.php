<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ChildSubject;
use AppBundle\Entity\Subject;
use AppBundle\Form\SubjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\VarDumper;

class SubjectController extends Controller
{
    /**
     * @Route("/subject/create", name="add_subject")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $subjects = $em->getRepository('AppBundle:Subject')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $data['subjects'] = $subjects;


        $subject = new Subject();
        $subject->setUser($user);

        $form = $this->createForm(SubjectType::class, $subject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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
        return $this->render('subject/create.html.twig',['form' => $form->createView(), 'data'=>$data] );

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
                array('id' => 'ASC')
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
            $data['form'] = $form_data;
        }

        $data['subject'] = $subject;


        return $this->render('subject/edit.html.twig', ['form' => $form->createView(), 'data' => $data,] );

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

            // exit(VarDumper::dump($childrenString));

        $em->remove($subject);
        $em->flush();

        return $this->redirectToRoute('list_subjects');

    }

    /**
     * @Route("/subject/profile/{subjectId}/{classId}", name="subject_profile")
     */
    public function subjectProfileAction(Request $request, $subjectId, $classId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $subject = $em->getRepository('AppBundle:Subject')
            ->find($subjectId);
        
        $exams = $em->getRepository('AppBundle:Exam')
            ->findBy(
                array('classs' => $classs, 'subject' => $subject),
                array('term' => 'ASC')
            );

        $using = 'subject';
        if(!$subject){
            $subject = $em->getRepository('AppBundle:ChildSubject')
                ->find($subjectId);

            $exams = $em->getRepository('AppBundle:Exam')
                ->findBy(
                    array('classs' => $classs, 'childSubject' => $subject),
                    array('term' => 'ASC')
                );
            $using = 'childSubject';
        }


        $data['subject'] = $subject;

        $examList = [];
        $examCompanies = [];
        $index = 0;
        foreach($exams as $exam){
            $examList[] = $exam;
            $examCompanies[$exam->getTerm()."_".$index] = $exam->getExamCompany()->getId();
            $index++;
        }

        $noOfStudents = count($classs->getStudents());

        $summations = [];
        foreach($examCompanies as $term => $singleCompany){
            $thisTerm = explode("_", $term)[0];
            $thisCompany = $em->getRepository('AppBundle:ExamCompany')
                ->find($singleCompany);

            if($using == 'subject'){
              $sumMarksForThisCompany = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForSubject($subject, $singleCompany, $thisTerm, $classs);
            } else {
              $sumMarksForThisCompany = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForChildSubject($subject, $singleCompany, $thisTerm, $classs);
            }
            $meanScore = $sumMarksForThisCompany / $noOfStudents;
            $summations[$thisCompany->getCompanyName()." Term $thisTerm"] = round($meanScore, 2);
        }

        $data['exams'] = $summations;
        $data['examList'] = $examList;
        $data['test'] = $examCompanies;

        return $this->render('subject/subject.html.twig', $data );
        
    }

    /**
     * @Route("/student/ajax/record", name="ajax_save_subject")
     */
    public function recordAction(Request $request)
    {

        if($request->request->get('subjectTitle')){
            $data = [];
            $subjectTitle = $request->request->get('subjectTitle');
            $role = $request->request->get('role');
            $parentName = $request->request->get('parentName');
            $children = $request->request->get('children');
            $outOf1 = $request->request->get('outOf1');
            $outOf2 = $request->request->get('outOf2');

            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();


            if($role == "parent"){ // or null

                $subjectPar = new Subject();
                $subjectPar->setSubjectTitle($parentName);
                $subjectPar->setOutOf('children');
                $subjectPar->setUser($user);
                $em->persist($subjectPar);
                $em->flush();

                $childrens = explode("_", $children);
                $pointer = 0;
                foreach($childrens as $child){
                    $pointer += 1;
                    $subject = new ChildSubject();
                    $subject->setSubjectTitle($child);
                    $subject->setParent($subjectPar);
                    if($pointer == 1){
                        $subject->setOutOf($outOf1);
                    } else if ($pointer == 2 ){
                        $subject->setOutOf($outOf2);
                    }
                    
                    $subject->setUser($user);

                    $em->persist($subject);
                    $em->flush();
                }




            } else {
                $subject = new Subject();
                $subject->setSubjectTitle($subjectTitle);
                $subject->setOutOf(100);
                $subject->setUser($user);

                $em->persist($subject);
                $em->flush();
            }



            $data['subjectTitle'] = $subjectTitle;
            $data['role'] = $role;
            $data['children'] = $children;
            $data['outOf1'] = $outOf1;
            $data['outOf2'] = $outOf2;
            $data['parentName'] = $parentName;
        }

        $this->addFlash(
            'success',
            'Subject added successfully!'
        );

            return new JsonResponse($data);

    }

    /**
     * @Route("/student/ajax/edit", name="ajax_edit_subject")
     */
    public function ajaxEditAction(Request $request)
    {

        if($request->request->get('subjectTitle')){
            $data = [];
            $id = $request->request->get('id');
            $subjectTitle = $request->request->get('subjectTitle');
            $role = $request->request->get('role');
            $parentName = $request->request->get('parentName');
            $children = $request->request->get('children');
            $outOf1 = $request->request->get('outOf1');
            $outOf2 = $request->request->get('outOf2');

            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();

            if($role == "parent"){ // or null

                $subjectPar = $em->getRepository('AppBundle:Subject')
                    ->find($id);
                $subjectPar->setSubjectTitle($parentName);
                $subjectPar->setOutOf('children');
                $subjectPar->setUser($user);
                $em->persist($subjectPar);
                $em->flush();

                $childrens = $subjectPar->getChildSubjects();
                $pointer = 0;
                foreach($childrens as $subject){
                    $pointer += 1;
                    $subject->setParent($subjectPar);
                    if($pointer == 1){
                        $subject->setOutOf($outOf1);
                    } else if ($pointer == 2 ){
                        $subject->setOutOf($outOf2);
                    }
                    
                    $subject->setUser($user);

                    $em->persist($subject);
                    $em->flush();
                }




            } else {
                $subject = new Subject();
                $subject->setSubjectTitle($subjectTitle);
                $subject->setOutOf(100);
                $subject->setUser($user);

                $em->persist($subject);
                $em->flush();
            }


            $data['subjectTitle'] = $subjectTitle;
            $data['role'] = $role;
            $data['children'] = $children;
            $data['outOf1'] = $outOf1;
            $data['outOf2'] = $outOf2;
            $data['parentName'] = $parentName;
        }

        $this->addFlash(
            'success',
            'Subject added successfully!'
        );

            return new JsonResponse($data);

    }



}