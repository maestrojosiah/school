<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $subjectsForTotal = [];
        $uniquer = 0;
        foreach($subjects as $subject){
            if($subject->getOutOf() == "children"){
                foreach($subject->getChildSubjects() as $singleSubj){
                    $subjectsForTotal['child_'.$uniquer] = $singleSubj;
                    $uniquer++;
                }
            } else {
                $subjectsForTotal['noRole_'.$uniquer] = $subject;
                $uniquer++;
            }
            
        }


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
            foreach($subjectsForTotal as $key=>$subject){
                $role = explode('_', $key)[0];
                    $exams = $em->getRepository('AppBundle:Exam')
                      ->getOneExam($student,$subject,$examCompany,$term);

                if($exams){
                    if($exams[0]->getSubject() != null ){
                        $subjId = $exams[0]->getSubject()->getId();
                    } else {
                        $subjId = $exams[0]->getChildSubject()->getId();
                    }
                    $key = $exams[0]->getStudent()->getId().$subjId;
                    $exam[$key] = $exams[0];
                }
                
            }
        }

        $data['examCompanies'] = $examCompanies;
        $data['examCompany'] = $examCompany;
        $data['students'] = $students;
        $data['subjects'] = $subjectsForTotal;
        $data['test'] = $subjectsForTotal;
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

        $subjectsForTotal = [];
        $uniquer = 0;
        foreach($subjects as $subject){
            if($subject->getOutOf() == "children"){
                foreach($subject->getChildSubjects() as $singleSubj){
                    $subjectsForTotal['child_'.$uniquer] = $singleSubj;
                    $uniquer++;
                }
                $subjectsForTotal['parent_'.$uniquer] = $subject;
            } else {
                $subjectsForTotal['noRole_'.$uniquer] = $subject;
                $uniquer++;
            }
            
        }

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
            foreach($subjectsForTotal as $key=>$subject){
                $role = explode('_', $key)[0];
                $exams = $em->getRepository('AppBundle:Exam')
                    ->getOneExam($student,$subject,$examCompany,$term);
                
                if($exams){
                    if($exams[0]->getSubject() != null ){
                        $subjId = $exams[0]->getSubject()->getId();
                    } else {
                        $subjId = $exams[0]->getChildSubject()->getId();
                    }
                    $key = $exams[0]->getStudent()->getId().'.'.$subjId;
                    $exam[$key] = $exams[0];
                }
                
            }

            $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForStudent($student, $examCompany, $term);
            $totalMarksStudent[$student->getId()] = $marks;

        }

        $totalMarksSubject = [];
        foreach($subjectsForTotal as $key=>$subject){
            $role = explode('_', $key)[0];
            if($role == 'child'){
              $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForChildSubject($subject, $examCompany, $term, $class);
            } else {
              $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForSubject($subject, $examCompany, $term, $class);
            }
            $totalMarksSubject[$subject->getId()] = $marks;
        }

        $rank = $this->rank($totalMarksStudent);
        $rankSubj = $this->rank($totalMarksSubject);



        $data['examCompanies'] = $examCompanies;
        $data['examCompany'] = $examCompany;
    	$data['subjects'] = $subjectsForTotal;
        $data['students'] = $students;
        $data['class'] = $class;

        $data['exam'] = $exam;
        $data['test'] = $totalMarksSubject;

        $data['term'] = $term;
        $data['rank'] = $rank;
        $data['rankSubj'] = $rankSubj;
        $data['totalMarksStudent'] = $totalMarksStudent;
        $data['totalMarksSubject'] = $totalMarksSubject;
    	$data['totalMarks'] = array_sum($totalMarksStudent);

        return $this->render('exam/view.html.twig', $data );

    }

    public function rank ($arr) {
        arsort($arr);
        $ret = array();
        $s = array();
        $i = 0;
        foreach ($arr as $k=>$v) {
            $em = $this->getDoctrine()->getManager();
            $stude = $em->getRepository('AppBundle:Student')
                ->find($k);
            if (!isset($s[$v])) { $s[$v] = ++$i; } else { ++$i; }
            $ret[$k]= array($v, $s[$v], $stude);
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

        $data['exams'] = array_unique($exams);
        $data['class'] =$class;

        return $this->render('exam/company.html.twig', $data);

    }

        /**
     * @Route("/exam/profile/{classId}", name="exam_profile")
     */
    public function examProfileAction(Request $request, $classId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $exams = $em->getRepository('AppBundle:Exam')
            ->findBy(
                array('classs' => $classs),
                array('term' => 'ASC')
            );

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
            $thisClass = $classs->getClassNumber();
            $thisCompany = $em->getRepository('AppBundle:ExamCompany')
                ->find($singleCompany);
            $sumMarksForThisCompany = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForCompany($singleCompany, $thisTerm);
            $meanScore = $sumMarksForThisCompany / $noOfStudents;
            $summations[$thisCompany->getCompanyName()." Class $thisClass, Term $thisTerm"] = round($meanScore, 2);
        }

        $data['exams'] = $summations;
        $data['class'] = $thisClass;
        $data['test'] = $examCompanies;

        return $this->render('exam/exam.html.twig', $data );
        
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
            $role = $request->request->get('role');
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

            $class = $em->getRepository('AppBundle:Classs')
                ->find($thisClass);

            if($role != 'child'){
                $subject = $em->getRepository('AppBundle:Subject')
                ->find($subject_id);

                $childSubject = NULL;

                $isAlreadyRecorded = $em->getRepository('AppBundle:Exam')
                    ->isAlreadyRecorded($student, $class, $subject, $examCompany, $thisTerm);

            } else {
                $childSubject = $em->getRepository('AppBundle:ChildSubject')
                ->find($subject_id);

                $subject = NULL;

                $isAlreadyRecorded = $em->getRepository('AppBundle:Exam')
                    ->isAlreadyRecordedChild($student, $class, $childSubject, $examCompany, $thisTerm);

            }

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
            $exam->setChildSubject($childSubject);
            $exam->setSubjectRole($role);
            $exam->setUser($user);

            $em->persist($exam);
            $em->flush();

            if ($role == "child"){
                //find the parent subject
                $subjectPar = $childSubject->getParent();

                //find the counterpart child subject
                $children = $subjectPar->getChildSubjects();

                $firstChild = $children[0];
                $secondChild = $children[1];


                $summation = $firstChild->getOutOf()+$secondChild->getOutOf();

                $imTypingOnWhichChild = $childSubject == $firstChild ? $firstChild : $secondChild;
                $imNotTypingOnWhichChild = $childSubject != $firstChild ? $firstChild : $secondChild;

                $isAlreadyRecorded = $em->getRepository('AppBundle:Exam')
                    ->isAlreadyRecorded($student, $class, $subjectPar, $examCompany, $thisTerm);

                if($isAlreadyRecorded){
                    $exam = $isAlreadyRecorded;
                    $message = "[$marks] Edited successfully!";
                } else {
                    $exam = new Exam();
                    $message = "[$marks] Created successfully!";
                }

                $counterMarksQ = $em->getRepository('AppBundle:Exam')
                    ->findBy(
                        array('childSubject' => $imNotTypingOnWhichChild, 'student' => $student, 'classs' => $class, 'examCompany' => $examCompany),
                        array('childSubject' => 'ASC')
                    );

                if(!$counterMarksQ){
                    $counterMarks = 0;
                } else {
                    $counterMarks = $counterMarksQ[0]->getMarks();
                }

                $calculatedPercentage = round((($marks+$counterMarks)/$summation)*100);

                $exam->setMarks($calculatedPercentage);
                $exam->setExamCompany($examCompany);
                $exam->setTerm($thisTerm);
                $exam->setClasss($class);
                $exam->setStudent($student);
                $exam->setSubject($subjectPar);
                $exam->setChildSubject(NULL);
                $exam->setSubjectRole('parent');
                $exam->setUser($user);

                $em->persist($exam);
                $em->flush();

            } 



            $data['student_id'] = $student_id;
            $data['subject_id'] = $subject_id;
            $data['student'] = $student->getFName();
            $data['subject'] = (null != $subject) 
                ? $subject->getSubjectTitle() 
                : $childSubject->getSubjectTitle();
            $data['examCompany'] = $examCompany->getCompanyName();
            $data['marks'] = $marks;
            $data['term'] = $term;
            $data['thisClass'] = $thisClass;
            $data['message'] = $message;
            $data['class'] = $class->getClassNumber();
	    
        }

	    return new JsonResponse($data);

	}

    /**
     * Export to PDF
     * 
    /**
     * @Route("/exam/download/{classId}/{examCompanyId}/{term}", name="download_exams")
     */
    public function examPDFAction(Request $request, $classId, $examCompanyId, $term)
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

        $subjectsForTotal = [];
        $uniquer = 0;
        foreach($subjects as $subject){
            if($subject->getOutOf() == "children"){
                foreach($subject->getChildSubjects() as $singleSubj){
                    $subjectsForTotal['child_'.$uniquer] = $singleSubj;
                    $uniquer++;
                }
                $subjectsForTotal['parent_'.$uniquer] = $subject;
            } else {
                $subjectsForTotal['noRole_'.$uniquer] = $subject;
                $uniquer++;
            }
            
        }

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
            foreach($subjectsForTotal as $key=>$subject){
                $role = explode('_', $key)[0];
                $exams = $em->getRepository('AppBundle:Exam')
                    ->getOneExam($student,$subject,$examCompany,$term);
                
                if($exams){
                    if($exams[0]->getSubject() != null ){
                        $subjId = $exams[0]->getSubject()->getId();
                    } else {
                        $subjId = $exams[0]->getChildSubject()->getId();
                    }
                    $key = $exams[0]->getStudent()->getId().'.'.$subjId;
                    $exam[$key] = $exams[0];
                }
                
            }

            $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForStudent($student, $examCompany, $term);
            $totalMarksStudent[$student->getId()] = $marks;

        }

        $totalMarksSubject = [];
        foreach($subjectsForTotal as $key=>$subject){
            $role = explode('_', $key)[0];
            if($role == 'child'){
              $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForChildSubject($subject, $examCompany, $term, $class);
            } else {
              $marks = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForSubject($subject, $examCompany, $term, $class);
            }
            $totalMarksSubject[$subject->getId()] = $marks;
        }

        $rank = $this->rank($totalMarksStudent);
        $rankSubj = $this->rank($totalMarksSubject);

        $data['examCompanies'] = $examCompanies;
        $data['examCompany'] = $examCompany;
        $data['subjects'] = $subjectsForTotal;
        $data['students'] = $students;
        $data['class'] = $class;
        $data['exam'] = $exam;
        $data['term'] = $term;
        $data['rank'] = $rank;
        $data['rankSubj'] = $rankSubj;
        $data['totalMarksStudent'] = $totalMarksStudent;
        $data['totalMarksSubject'] = $totalMarksSubject;

        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/exam.html.twig', $data);

        $filename = sprintf("exam-%s.pdf", date('Ymd~his'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('orientation'=>'Landscape')),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}
