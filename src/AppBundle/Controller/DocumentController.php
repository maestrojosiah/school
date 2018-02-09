<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Document;
use AppBundle\Form\DocumentType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DocumentController extends Controller
{
    /**
     * @Route("/document/create", name="add_document")
     */
    public function createAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $document->getFile();
			$name = str_replace(" ", '-', $form->get('filename')->getData());
			$type = $file->getClientMimeType();
			
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'_'.$name.'.'.$file-> getClientOriginalExtension();
            $filesize = $file->getClientSize();
            $filepath = $this->getParameter('document_directory').'/'.$fileName;

            // Move the file to the directory where documents are stored
            $file->move(
                $this->getParameter('document_directory'),
                $fileName
            );

            // Update the 'document' property to store the file name
            // instead of its contents
            $document->setFilename($name);
            $document->setUser($user);
            $document->setType($type);
            $document->setSize($filesize);
            $document->setFile($filepath);

            $em->persist($document);
            $em->flush();

            // ... persist the $document variable or any other work

            return $this->redirect($this->generateUrl('list_documents'));
        }

        return $this->render('document/create.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    /**
     * @Route("/document/list", name="list_documents")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $documents = $em->getRepository('AppBundle:Document')
            ->findAll(
            	array('user' => $user),
            	array('id' => 'DESC')
            );

        $data['documents'] = $documents;

        return $this->render('document/list.html.twig', $data );

    }

    /**
     * @Route("/document/delete/{documentId}/{filepath}", name="delete_document")
     */
    public function deleteAction(Request $request, $documentId, $filepath)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('AppBundle:Document')
            ->find($documentId);

        $em->remove($document);
        if($em->flush()){
        	unlink(urldecode($filepath));
        }

        return $this->redirectToRoute('list_documents');

    }

    /**
     * @Route("/document/download/{filepath}", name="download_document")
     */
    public function downloadAction(Request $request, $filepath)
    {
		$response = new BinaryFileResponse(urldecode($filepath));
		$response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

		return $response;

    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
}