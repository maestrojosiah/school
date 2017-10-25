<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Exam;
use AppBundle\Form\ExamType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExamController extends Controller
{
    /**
     * @Route("/exam/create/{classId}/{examCompanyId}/{term}", name="add_exam_page")
     */
    public function createAction(Request $request, $classId, $examCompanyId, $term)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $examCompany = $em->getRepository('AppBundle:ExamCompany')
            ->find($examCompanyId);

        $subjects = $em->getRepository('AppBundle:Subject')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $examCompanies = $em->getRepository('AppBundle:ExamCompany')
            ->findAll();

        if(!$examCompanies){
            $this->addFlash(
                'success',
                'Please add at least one exam company'
            );

            return $this->redirectToRoute('add_company');
        }

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('user' => $user, 'classs' => $class),
                array('id' => 'ASC')
            );

        $exam = [];
        foreach($students as $student){
            foreach($subjects as $subject){
                $exams = $em->getRepository('AppBundle:Exam')
                    ->getOneExam($student,$subject,$examCompany,$term);
                if($exams){
                    $key = $exams[0]->getStudent()->getId().$exams[0]->getSubject()->getId();
                    $exam[$key] = $exams[0];
                }
                
            }
        }

        $data['examCompanies'] = $examCompanies;
        $data['examCompany'] = $examCompany;
        $data['subjects'] = $subjects;
        $data['students'] = $students;
        $data['class'] = $class;
        $data['exam'] = $exam;
        $data['term'] = $term;

        return $this->render('exam/home.html.twig', $data );

    }
    
    /**
     * @Route("/exam/view/{classId}/{examCompanyId}/{term}", name="view_exams")
     */
    public function viewAction(Request $request, $classId, $examCompanyId, $term)
    {
    	$data = [];
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	$em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

    	$examCompany = $em->getRepository('AppBundle:ExamCompany')
    		->find($examCompanyId);

        $subjects = $em->getRepository('AppBundle:Subject')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

    	$examCompanies = $em->getRepository('AppBundle:ExamCompany')
    		->findAll();

        if(!$examCompanies){
            $this->addFlash(
                'success',
                'Please add at least one exam company'
            );

            return $this->redirectToRoute('add_company');
        }

    	$students = $em->getRepository('AppBundle:Student')
    		->findBy(
    			array('user' => $user, 'classs' => $class),
    			array('id' => 'ASC')
    		);

        $exam = [];
        $totalMarksStudent = [];
        
        foreach($students as $student){
            foreach($subjects as $subject){
                $exams = $em->getRepository('AppBundle:Exam')
                    ->getOneExam($student,$subject,$examCompany,$term);
                if($exams){
                    $key = $exams[0]->getStudent()->getId().$exams[0]->getSubject()->getId();
                    $exam[$key] = $exams[0];
                }
                
            }

            $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForStudent($student, $examCompany, $term);
            $totalMarksStudent[$student->getId()] = $marks;

        }

        $totalMarksSubject = [];
        foreach($subjects as $subject){
            $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForSubject($subject, $examCompany, $term);
            $totalMarksSubject[$subject->getId()] = $marks;
        }
        $rank = $this->rank($totalMarksStudent);


        $data['examCompanies'] = $examCompanies;
        $data['examCompany'] = $examCompany;
    	$data['subjects'] = $subjects;
        $data['students'] = $students;
        $data['class'] = $class;
        $data['exam'] = $exam;
        $data['term'] = $term;
        $data['rank'] = $rank;
        $data['totalMarksStudent'] = $totalMarksStudent;
    	$data['totalMarksSubject'] = $totalMarksSubject;

        return $this->render('exam/view.html.twig', $data );

    }

    public function rank ($arr) {

        $ret = array();
        $s = array();
        $i = 0;
        foreach ($arr as $k=>$v) {
            if (!isset($s[$v])) { $s[$v] = ++$i; } else { ++$i; }
            $ret[$k]= array($v, $s[$v]);
        }
        return $ret;
    }
    
    /**
     * @Route("/exam/choose", name="choose_class_for_exam")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('exam/class.html.twig');

    }

    /**
     * @Route("/exam/company/{classId}", name="choose_exam_company")
     */
    public function chooseCompanyAction(Request $request, $classId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $exams = $em->getRepository('AppBundle:Exam')
            ->findCompanies($class);

        if(!$exams){
            $this->addFlash(
                'success',
                'Please add at least one exam company'
            );


            return $this->redirectToRoute('add_company');
        }

        $data['exams'] = array_unique($exams);
        $data['class'] =$class;

        return $this->render('exam/company.html.twig', $data);

    }

    /**
     * @Route("/exam/ajax/record", name="record_exam_ajax")
     */
    public function recordAction(Request $request)
    {

        if($request->request->get('subject_id')){
            $data = [];
            $subject_id = $request->request->get('subject_id');
            $student_id = $request->request->get('student_id');
            $term = $request->request->get('term');
            $splitTerm = explode('_', $term);
            $thisTerm = $splitTerm[1];
            $examCompanyId = $request->request->get('examCompany');
            $thisClass = $request->request->get('class');
            $marks = $request->request->get('marks');
            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();

            $student = $em->getRepository('AppBundle:Student')
                ->find($student_id);

            $examCompany = $em->getRepository('AppBundle:ExamCompany')
                ->find($examCompanyId);

            $subject = $em->getRepository('AppBundle:Subject')
                ->find($subject_id);

            $class = $em->getRepository('AppBundle:Classs')
                ->find($thisClass);

            $isAlreadyRecorded = $em->getRepository('AppBundle:Exam')
                ->isAlreadyRecorded($student, $class, $subject, $examCompany);

            if($isAlreadyRecorded){
                $exam = $isAlreadyRecorded;
                $message = "[$marks] Edited successfully!";
            } else {
                $exam = new Exam();
                $message = "[$marks] Created successfully!";
            }

            
            $exam->setMarks($marks);
            $exam->setExamCompany($examCompany);
            $exam->setTerm($thisTerm);
            $exam->setClasss($class);
            $exam->setStudent($student);
            $exam->setSubject($subject);
            $exam->setUser($user);

            $em->persist($exam);
            $em->flush();
            $em->flush();

            $data['student_id'] = $student_id;
            $data['subject_id'] = $subject_id;
            $data['student'] = $student->getFName();
            $data['subject'] = $subject->getSubjectTitle();
            $data['examCompany'] = $examCompany->getCompanyName();
            $data['marks'] = $marks;
            $data['term'] = $term;
            $data['thisClass'] = $thisClass;
            $data['message'] = $message;
	    
        }
	    // $this->addFlash(
	    //     'success',
	    //     'Attendances marked successfully!'
	    // );

	    return new JsonResponse($data);

	}


}