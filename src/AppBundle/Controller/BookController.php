<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\VarDumper;

class BookController extends Controller
{
    /**
     * @Route("/book/create", name="add_book")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Book created successfully!'
        	);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_book'
                : 'homepage';

            return $this->redirectToRoute($nextAction);

		} 

	
        // replace this example code with whatever you need
        return $this->render('book/create.html.twig',['form' => $form->createView()] );

    }

    /**
     * @Route("/book/list", name="list_books")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $books = $em->getRepository('AppBundle:Book')
            ->findAll();

        $data['books'] = $books;

        return $this->render('book/list.html.twig', $data );

    }

    /**
     * @Route("/book/edit/{bookId}", name="edit_book")
     */
    public function editAction(Request $request, $bookId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $book = $em->getRepository('AppBundle:Book')
            ->find($bookId);


        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Book edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_book'
                : 'list_books';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['book_title'] = $book->getBookTitle();
            $data['form'] = $form_data;
        }

        $data['book'] = $book;


        return $this->render('book/edit.html.twig', ['form' => $form->createView(), 'data' => $data,] );

    }

    /**
     * @Route("/book/delete/{bookId}", name="delete_book")
     */
    public function deleteAction(Request $request, $bookId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $book = $em->getRepository('AppBundle:Book')
            ->find($bookId);

            // exit(VarDumper::dump($childrenString));

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('list_books');

    }


}