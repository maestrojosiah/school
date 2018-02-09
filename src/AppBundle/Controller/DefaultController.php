<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('AppBundle:Student')
        	->countStudents($user);
        $data['countStudents'] = $students;

        $subjects = $em->getRepository('AppBundle:Subject')
        	->countSubjects($user);
        $data['countSubjects'] = $subjects;

        $classes = $em->getRepository('AppBundle:Classs')
            ->countClasses($user);
        $data['countClasses'] = $classes;

        $exams = $em->getRepository('AppBundle:Exam')
            ->countExams($user);
        $data['countExams'] = $exams;

        $config = $em->getRepository('AppBundle:Config')
        	->findBy(
                array('user' => $user),
                array('id' => 'DESC')
            );
        $data['config'] = $config;

        if($classes == 0){
            $this->addFlash(
                'success',
                'Please add at least one class!'
            );
            return $this->redirectToRoute('add_class');
        }

        if($students == 0){
            $this->addFlash(
                'success',
                'Please add at least one student!'
            );
            return $this->redirectToRoute('add_student');
        }

        if($subjects == 0){
            $this->addFlash(
                'success',
                'Please add at least one subject!'
            );
            return $this->redirectToRoute('add_subject');
        }

        if(!$config){
            $this->addFlash(
                'success',
                'Some few more settings! You will need this in your documents'
            );
            return $this->redirectToRoute('change_settings');
        }


        return $this->render('default/index.html.twig', $data);
    }
}
