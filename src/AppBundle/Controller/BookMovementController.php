<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\BookMovement;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\VarDumper;

class BookMovementController extends Controller
{
    /**
     * @Route("/book_movement/create", name="add_book_movement")
     */
    public function createAction(Request $request)
    {

        return $this->render('book_mov/create.html.twig' );
    }

    /**
     * @Route("/bk_movement/classes/list", name="list_book_classes")
     */
    public function listClassesAction(Request $request)
    {

        $data = [];

        $user = $this->get('security.token_storage')->getToken()->getUser();
      
        $em = $this->getDoctrine()->getManager();

		$classes = $em->getRepository('AppBundle:Classs')
			->findBy(
				array('user' => $user),
				array('id' => 'ASC')
			);
		$data['classes'] = $classes;
		$html = '<option value="null">Which Class?</option>';
		foreach($classes as $class){
			$html .= '<option value="'.$class->getId().'">'.$class->getClassNumber().'</option>';
		}
		$data['html'] = $html;

        return new JsonResponse($data);

	}

    /**
     * @Route("/book_movement/students", name="list_book_students")
     */
    public function listStudentsAction(Request $request)
    {

        if($request->request->get('classs')){
            $data = [];
            $em = $this->getDoctrine()->getManager();

            $classsId = $request->request->get('classs');
            $classs = $em->getRepository('AppBundle:Classs')
            	->find($classsId);

            $user = $this->get('security.token_storage')->getToken()->getUser();
          

			$students = $em->getRepository('AppBundle:Student')
				->findBy(
					array('user' => $user, 'classs' => $classs),
					array('id' => 'ASC')
				);
			$data['students'] = $students;
			$html = '<option value="null">Which Student?</option>';
			foreach($students as $student){
				$html .= '<option value="'.$student->getId().'">'.$student.'</option>';
			}
			$data['html'] = $html;

            return new JsonResponse($data);
        }

    }

    /**
     * @Route("/book_movement/list/books", name="list_which_books")
     */
    public function listBooksAction(Request $request)
    {

        $data = [];

        $user = $this->get('security.token_storage')->getToken()->getUser();
      
        $em = $this->getDoctrine()->getManager();

        // get the selected student
        $studentId = $request->request->get('student');
        $student = $em->getRepository('AppBundle:Student')
        	->find($studentId);

        // get the books owned by this student
    	$BookMovements = $student->getBookMovements();

    	$booksTheyHave = [];
    	foreach($BookMovements as $bkMv){
    		$where = $bkMv->getInOrOut();
    		$booksTheyHave[$bkMv->getBook()->getId()] = $bkMv->getBook();
    	}

    	$movements = [];
    	foreach($BookMovements as $mvmt){
			$movements[] =  $mvmt;   	
    	}

    	// get the available created books
		$booksAvailable = $em->getRepository('AppBundle:Book')
			->findAll();
    	if($booksAvailable){
        	$books = [];
        	$pointer = 0;
        	foreach($booksAvailable as $bkAv){
        		$pointer++;
        		$books[$pointer.'|'] = $bkAv;
        	}
    	} else {
    		$books = null;
    	}
        
        $data['books'] = $books;
        $html = "<tr>
                    <th>#</th>
                    <th>Take</th>
                </tr>
        		";
        $counter = 0;
        foreach($books as $key=>$book){
        	$status = explode("|", $key)[1];
        	$counter++;
        	$html .= '
        	<tr>
                <td>'.$counter.'</td>
                <td><button type="button" class="btn btn-default btn-block" id="book_firstTake_'. $book->getId() .'">Store '.$student->getFName().'\'s '.$book->getBookTitle().'</button></td>
            </tr>
        	';
        }

        $htmlHave = "<tr>
                        <th>Take</th>
                        <th>Give</th>
                        <th>Status</th>
                    </tr>
            		";
        foreach($booksTheyHave as $key=>$bookTheyHave){
        	$status = $key;
			// check last entry if exists then record
            $lastEntry = $em->getRepository('AppBundle:BookMovement')
            	->findOneBy(
            		array('owner' => $student, 'book' => $bookTheyHave),
            		array('id' => 'DESC')
            	);
            $showStatus = $lastEntry->getInOrOut() == "in" ? "<span style='font-size:34; font-weight:bold;' class='text-success'>".$lastEntry->getInOrOut()."</span>" : "<span style='font-size:34; font-weight:bold;' class='text-danger'>".$lastEntry->getInOrOut()."</span>" ;
        	$htmlHave .= '
            	<tr>
                    <td><button type="button" class="btn btn-info btn-block" id="book_take_'. $bookTheyHave->getId() .'">Store '.$bookTheyHave->getBookTitle().'</button></td>
                    <td><button type="button" class="btn btn-primary btn-block" id="book_give_'. $bookTheyHave->getId() .'">Hand Over '.$bookTheyHave->getBookTitle().'</button></td>
                    <td class="text-center" id="toUpdate_'.$bookTheyHave->getId().'">'.$showStatus.'</td>
                </tr>
                ';
        }

        $htmlMv = "<tr>
                        <th>Date</th>
                        <th>Book</th>
                        <th>Details</th>
                    </tr>
            		";
        foreach($movements as $movement){

        	$htmlMv .= '
            	<tr>
            		<td>'.$movement->getOnDate()->format('Y-m-d').'</td>
            		<td>'.$movement->getBook()->getBookTitle().'</td>
                    <td>'.$movement->getInOrOut().'</td>
                </tr>
                ';
        }

        $data['html'] = $html;
        $data['htmlHave'] = $htmlHave;
        $data['htmlMv'] = $htmlMv;
        $data['student'] = $student->getFName();
    	
        return new JsonResponse($data);

	}

    /**
     * @Route("/book_mv/ajax/record", name="ajax_save_bk_movement")
     */
    public function recordAction(Request $request)
    {

        if($request->request->get('give_or_take')){
            $data = [];
            $give_or_take = $request->request->get('give_or_take');
            $classs = $request->request->get('classs');
            $student = $request->request->get('student');
            $id = $request->request->get('id');

            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();

            $inOrOut = $give_or_take == 'take' ? 'in' : 'out' ;
            if($give_or_take == 'firstTake'){
            	$inOrOut = 'in';
            }

            $classsId = $request->request->get('classs');
            $classs = $em->getRepository('AppBundle:Classs')
            	->find($classsId);

            $studentId = $request->request->get('student');
            $student = $em->getRepository('AppBundle:Student')
            	->find($studentId);

            $book = $em->getRepository('AppBundle:Book')
            	->find($id);

			// check last entry if exists then record
            $lastEntry = $em->getRepository('AppBundle:BookMovement')
            	->findOneBy(
            		array('owner' => $student, 'book' => $book),
            		array('id' => 'DESC')
            	);

            if($lastEntry && $inOrOut == $lastEntry->getInOrOut()){
            	$bookMovement = $lastEntry;
            } else {
            	$bookMovement = new BookMovement();
            }
            
	       	//today in date format
		    $today = date("Y-m-d");
	        //set date to attendance
	    	$data['todays_date'] = new \DateTime($today);
	    	$bookMovement->setOnDate(new \DateTime($today));

            $bookMovement->setUser($user);
            $bookMovement->setInOrOut($inOrOut);
            $bookMovement->setOwner($student);
            $bookMovement->setBook($book);
            $em->persist($bookMovement);
            $em->flush();

            $data['lastEntry'] = $inOrOut;
            $data['book'] = $book->getBookTitle();


            return new JsonResponse($data);

	    }
	}


}