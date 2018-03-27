<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Student;
use AppBundle\Entity\Attendance;
use AppBundle\Form\StudentType;


class StudentController extends Controller
{
    /**
     * @Route("/student/create", name="add_student")
     */
    public function createAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('user'=>$user),
                array('id' => 'ASC')
            );

        $data['students'] = $students;

        //today, this year and this month in date format
        $today = date("Y-m-d");
        $year = date("Y");
        $month = date("m");

        //beginning of month and today's date
        $thisMonth = new \DateTime($today);
        $endDate =  new \DateTime($today);
        $startDate = $thisMonth->modify('first day of this month');

        //get the dates interval object
        $period = new \DatePeriod(
             $startDate, new \DateInterval('P1D'), $endDate
        );

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['period'] = $period;

        $student = new Student();
        $student->setUser($user);

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($student);
            $em->flush();

            //iterate through the dates while creating attendances
            foreach($period as $onDate){
                $morning = false;
                $afternoon = false;
                $attendance = new Attendance();
                $attendance->setStudent($student);
                $attendance->setOnDate($onDate);
                $attendance->setMorning($morning);
                $attendance->setAfternoon($afternoon);
                $em->persist($attendance);
                $em->flush();
            }

            $this->addFlash(
                'success',
                'Student created successfully!'
            );

            // record attendances for all dates of this month before today.

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_student'
                : 'homepage';

            return $this->redirectToRoute($nextAction);
        } 

            return $this->render('student/create.html.twig',['form' => $form->createView(), 'data' => $data] );

    }

    /**
     * @Route("/student/list", name="list_students")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('user' => $user),
                array('fName' => 'ASC')
            );

        $data['students'] = $students;

        return $this->render('student/list.html.twig', $data );

    }

    /**
     * @Route("/student/show/{classId}", name="list_students_from_class")
     */
    public function classListAction(Request $request, $classId)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('user' => $user, 'classs' => $class),
                array('fName' => 'ASC')
            );

        $data['students'] = $students;

        return $this->render('student/list.html.twig', $data );

    }

    /**
     * @Route("/student/edit/{studentId}", name="edit_student")
     */
    public function editAction(Request $request, $studentId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $student = $em->getRepository('AppBundle:Student')
            ->find($studentId);


        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Student edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_student'
                : 'list_students';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['f_name'] = $student->getFName();
            $form_data['l_name'] = $student->getLName();
            $form_data['contact'] = $student->getContact();
            $form_data['age'] = $student->getAge();
            $form_data['class'] = $student->getClasss();
            $data['form'] = $form_data;
        }
        $data['student'] = $student;


        return $this->render('student/edit.html.twig', ['form' => $form->createView(), $data,] );

    }

    /**
     * @Route("/student/delete/{studentId}", name="delete_student")
     */
    public function deleteAction(Request $request, $studentId)
    {
    	$data = [];
        $em = $this->getDoctrine()->getManager();

        $student = $em->getRepository('AppBundle:Student')
        	->find($studentId);

        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('list_students');

    }

    /**
     * @Route("/student/profile/{studentId}", name="student_profile")
     */
    public function studentAction(Request $request, $studentId )
    {   
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $student = $em->getRepository('AppBundle:Student')
            ->find($studentId);

        $data['student'] = $student;
        $attendances = $student->getAttendances();
        $exams = $student->getExams();

        $attendedPoints = 0;
        $presentPoints = 0;
        $absentPoints = 0;
        $totalPoints = 0;

        $daily = [];
        $counter = 0;
        foreach($attendances as $attn){
            $counter += 1;
            if($attn->getMorning() == true && $attn->getAfternoon() == true){
                $attendedPoints += 10;
                $presentPoints = 10;
                $absentPoints = 0;
                $totalPoints += 10;
            }
            if($attn->getMorning() == true && $attn->getAfternoon() == false){
                $attendedPoints += 5;
                $presentPoints = 5;
                $absentPoints = 5;
                $totalPoints += 10;
            }
            if($attn->getMorning() == false && $attn->getAfternoon() == true){
                $attendedPoints += 5;
                $presentPoints = 5;
                $absentPoints = 5;
                $totalPoints += 10;
            }
            if($attn->getMorning() == false && $attn->getAfternoon() == false){
                $attendedPoints += 0;
                $presentPoints = 0;
                $absentPoints = 10;
                $totalPoints += 10;
            }

            $date = $attn->getOnDate()->format('Y-m-d');
            $daily[$counter] = '{ score:"'.$date.'",' . 'present:'.$presentPoints . "," . 'absent:'.$absentPoints . ' }';
            if($counter == 61 ){
                break;
            }
        }

        $examList = [];
        $key = null;
        $limiter = 0;
        foreach($exams as $exam){
            $limiter += 1;

            $key = $exam->getExamCompany()->getId().$exam->getTerm();

            if (isset($examList[$key])) {
                $examList[$key][] = $exam;
            } else {
                $examList[$key] = array($exam);
            }
            if($limiter == 50 ){
                break;
            }
        }


        $data['attendedPoints'] = $attendedPoints;
        $data['presentPoints'] = $presentPoints;
        $data['absentPoints'] = $absentPoints;
        $data['totalPoints'] = $totalPoints;
        $data['daily'] = $daily;
        $data['examList'] = $examList;

        return $this->render('student/student.html.twig', $data);

    }

}