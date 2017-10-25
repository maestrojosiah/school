<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Attendance;
use AppBundle\Form\AttendanceType;
use Symfony\Component\HttpFoundation\JsonResponse;

class AttendanceController extends Controller
{

    /**
     * @Route("/attendance/create/{class}/{studentId}", name="record_attendance")
     */
    public function createAction(Request $request, $class, $studentId = null )
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        //find the class from the url
        $theClass = $em->getRepository('AppBundle:Classs')
        	->find($class);

        //check if studentId is provided from the url
        // if not, get the first student from database
        if( null === $studentId){
        	$student = $em->getRepository('AppBundle:Student')
        		->getFirstStudent($user, $theClass);
        } else {
        	$student = $em->getRepository('AppBundle:Student')
        		->find($studentId);
        }

        // if there's no student at all for the class, redirect back to classes
        if(!$student){
        	$this->addFlash(
	            'error',
	            'No students for that class!'
        	);
            return $this->redirectToRoute('attendance_classes');
        }

        // if there is a student, get the next student from db and assign it to property nextStudent
        $nextStudent = $em->getRepository('AppBundle:Student')
        		->next($student->getId(), $class);

        // if there is a student, get the previous student from db and assign it to property previousStudent
        $previousStudent = $em->getRepository('AppBundle:Student')
        		->previous($student->getId(), $class);

       	//today in date format
	    $today = date("Y-m-d");
	    //create a new attendance entity
    	$attendance = new Attendance();
    	//set student to attendance
        $attendance->setStudent($student);
        //set date to attendance
    	$data['todays_date'] = new \DateTime($today);
    	$attendance->setOnDate(new \DateTime($today));

    	//create form from the attendance entity
        $form = $this->createForm(AttendanceType::class, $attendance);
        //handle the request for a new attendance record
        $form->handleRequest($request);

        //get the given date from the form above
 		$theDay = $form->getData();
 		//see if student is marked for the above date
        $marked = $em->getRepository('AppBundle:Attendance')
        		->isAlreadyMarked($theDay->getOnDate(), $student);

    	$data['marked'] = $marked;
    	//if student is marked, create a new attendance entity from the marked attendance instead of creating new attendance
        if($marked && $marked->getOnDate() == $attendance->getOnDate()){
        	$attendance = $em->getRepository('AppBundle:Attendance')
        		->find($marked->getId());
        	$form = $this->createForm(AttendanceType::class, $attendance);
        	$form->handleRequest($request);

        } 	        

        //submit form
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($attendance);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Attendance marked successfully!'
        	);

            if($form->get('saveAndAdd')->isClicked()){
            	if(isset($nextStudent[0])){
            		return $this->redirectToRoute('record_attendance', ['class' => $class, 'studentId' => $nextStudent[0]->getId()]);
            	} else {
            		return $this->redirectToRoute('attendance_classes');
            	}
            } else {
            	return $this->redirectToRoute('homepage');
            }

		} 

        $data['student'] = $student;
        $data['nextStudent'] = $nextStudent;
        $data['previousStudent'] = $previousStudent;
        $data['class'] = $theClass;
        $data['theDay'] = $theDay;

	
        // replace this example code with whatever you need
        return $this->render('attendance/create.html.twig', ['form' => $form->createView(), 'data' => $data] );


    }

    /**
     * @Route("/attendance/classes", name="attendance_classes")
     */
    public function attendanceAction(Request $request )
    {   

        return $this->render('attendance/class.html.twig');


    }

    /**
     * @Route("/attendance/view/{classId}/{dateString}", name="attendance_register")
     */
    public function viewAction(Request $request, $classId, $dateString = null )
    {   
        $em = $this->getDoctrine()->getManager();

        $today = date("Y-m-d", time());
        if( null === $dateString ) {
            $date = new \DateTime($today);
        } else {
            $date = new \DateTime($dateString);
        }
        
        $month = (int) date('m', strtotime($dateString));
        $year = (int) date('Y', strtotime($dateString));

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('classs' => $class),
                array('fName' => 'ASC')
            );

        $attendz = [];
        foreach($students as $student){
            $attendances = $em->getRepository('AppBundle:Attendance')
                ->findByDate($student, $year, $month);

            $attendz[] = $attendances;
        }

        $data['students'] = $students;
        $data['class'] = $class;
        $data['date'] = $date;
        $data['attendances'] = $attendz;

        $presentPoints = 0;
        $absentPoints = 0;
        $totalPoints = 0;
        foreach($attendz as $obj){
            foreach($obj as $attn){
                if($attn->getMorning() == true && $attn->getAfternoon() == true){
                    $presentPoints += 10;
                    $absentPoints += 0;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == true && $attn->getAfternoon() == false){
                    $presentPoints += 5;
                    $absentPoints += 5;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == false && $attn->getAfternoon() == true){
                    $presentPoints += 5;
                    $absentPoints += 5;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == false && $attn->getAfternoon() == false){
                    $presentPoints += 0;
                    $absentPoints += 10;
                    $totalPoints += 10;
                }
            }
        }
        $data['presentPoints'] = $presentPoints;
        $data['absentPoints'] = $absentPoints;
        $data['totalPoints'] = $totalPoints;
        return $this->render('attendance/view.html.twig', $data);


    }

    /**
     * @Route("/attendance/mark/{classId}/{date}", name="mark_all")
     */
    public function markAction(Request $request, $classId, $date )
    {   
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('classs' => $class),
                array('fName' => 'ASC')
            );

        $data['students'] = $students;
        $data['class'] = $class;
        $data['date'] = $date;
        return $this->render('attendance/list.html.twig', $data);

    }

    /**
     * @Route("/attendance/unmark/{classId}/{date}", name="mark_all_as_absent")
     */
    public function unmarkAction(Request $request, $classId, $date )
    {   
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('classs' => $class),
                array('fName' => 'ASC')
            );

        $data['students'] = $students;
        $data['class'] = $class;
        $data['date'] = $date;
        return $this->render('attendance/listAbsent.html.twig', $data);

    }

    /**
     * @Route("/attendance/ajax/record", name="record_attendance_ajax")
     */
    public function recordAction(Request $request)
    {

        if($request->request->get('array')){
            $data = [];
            $array = $request->request->get('array');
            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();

            $individualStudentRecords = explode("|", $array);

            $boolean2 = [];
            foreach($individualStudentRecords as $studentRecord){
                $cleanRecord = str_replace(array('"' , "]" , ","), '', $studentRecord);
                $big_portions = explode(",", $studentRecord);
                foreach ($big_portions as $smallPortion){
                    $portions = explode("_", $smallPortion);
                    $time = $portions[0];
                    $studentId = $portions[1];
                    $lastPortion = $portions[2];
                    $dateAndBoolean = explode("~", $lastPortion);
                        $date = $dateAndBoolean[0];
                        $boolean = str_replace(array('"' , "]" , ","), '', $dateAndBoolean[1]);

                    $student = $em->getRepository('AppBundle:Student')
                        ->find($studentId);

                    $data_object =  new \DateTime($date);

                    // see if student is marked for the above date
                    $marked = $em->getRepository('AppBundle:Attendance')
                            ->isAlreadyMarked($date, $student);

                    // if student is marked, create a new attendance entity from the marked attendance instead of creating new attendance
                    if($marked && $marked->getOnDate() == $data_object){
                        $attendance = $em->getRepository('AppBundle:Attendance')
                            ->find($marked->getId());
                        $afternoon = $attendance->getAfternoon();
                        $morning = $attendance->getMorning();
                    } else {
                        $attendance = new Attendance();
                    }     
                    
                    $attendance->setStudent($student);
                    $attendance->setOnDate(new \DateTime($date));

                    if($boolean == 'mtrue'){
                        $attendance->setMorning(true);
                        $attendance->setAfternoon(false);
                    } else if($boolean == 'mfalse'){
                        $attendance->setMorning(false);
                        $attendance->setAfternoon(false);
                    } else if($boolean == 'atrue'){
                        $attendance->setMorning($morning);
                        $attendance->setAfternoon(true);
                    } else if($boolean == 'afalse'){
                        $attendance->setAfternoon($morning);
                        $attendance->setAfternoon(false);
                    } 

                    $boolean2[] = $attendance->getAfternoon();
                    //$boolean2[] = $attendance->getAfternoon();

                    $em->persist($attendance);
                    $em->flush();


                }
            }

            $data['time'] = $boolean2;
        }
        $this->addFlash(
            'success',
            'Attendances marked successfully!'
        );

            return new JsonResponse($data);

    }


    /**
     * Export to PDF
     * 
     * @Route("attendance/download/{classId}/{dateString}", name="download_attendance_pdf")
     */
    public function downloadPDFAction($classId, $dateString)
    {
        $em = $this->getDoctrine()->getManager();

        $today = date("Y-m-d", time());
        if( null === $dateString ) {
            $date = new \DateTime($today);
        } else {
            $date = new \DateTime($dateString);
        }
        
        $month = (int) date('m', strtotime($dateString));
        $year = (int) date('Y', strtotime($dateString));

        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $students = $em->getRepository('AppBundle:Student')
            ->findBy(
                array('classs' => $class),
                array('fName' => 'ASC')
            );

        $attendz = [];
        foreach($students as $student){
            $attendances = $em->getRepository('AppBundle:Attendance')
                ->findByDate($student, $year, $month);

            $attendz[] = $attendances;
        }

        $data['students'] = $students;
        $data['class'] = $class;
        $data['date'] = $date;
        $data['attendances'] = $attendz;

        $presentPoints = 0;
        $absentPoints = 0;
        $totalPoints = 0;
        foreach($attendz as $obj){
            foreach($obj as $attn){
                if($attn->getMorning() == true && $attn->getAfternoon() == true){
                    $presentPoints += 10;
                    $absentPoints += 0;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == true && $attn->getAfternoon() == false){
                    $presentPoints += 5;
                    $absentPoints += 5;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == false && $attn->getAfternoon() == true){
                    $presentPoints += 5;
                    $absentPoints += 5;
                    $totalPoints += 10;
                }
                if($attn->getMorning() == false && $attn->getAfternoon() == false){
                    $presentPoints += 0;
                    $absentPoints += 10;
                    $totalPoints += 10;
                }
            }
        }
        $data['presentPoints'] = $presentPoints;
        $data['absentPoints'] = $absentPoints;
        $data['totalPoints'] = $totalPoints;

        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/attendance.html.twig', $data);

        $filename = sprintf("attendance-%s.pdf", date('Ymd~his'));

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