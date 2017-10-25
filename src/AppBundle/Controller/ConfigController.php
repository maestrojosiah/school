<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Config;
use AppBundle\Form\ConfigType;


class ConfigController extends Controller
{
    /**
     * @Route("/settings", name="change_settings")
     */
    public function setAction(Request $request)
    {
		$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $thisUser = $em->getRepository('AppBundle:Config')
        	->settingsForThisUser($user);

        if($thisUser){
        	$config = $thisUser;
        } else {
        	$config = new Config();
        }

        $config->setUser($user);
        $form = $this->createForm(ConfigType::class, $config);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $config->setUser($user);

            $em->persist($config);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Settings created successfully!'
        	);

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('homepage');
		} else {
			$form_data['school_name'] = $config->getSchoolName();
			$form_data['school_address'] = $config->getSchoolAddress();
			$form_data['school_telephone'] = $config->getSchoolTelephone();
			$form_data['document_header'] = $config->getDocumentHeader();
			$form_data['document_footer'] = $config->getDocumentFooter();
			$form_data['results_per_page'] = $config->getResultsPerPage();
			$data['form'] = $form_data;
		}

	
        // replace this example code with whatever you need
        return $this->render('config/configuration.html.twig',['form' => $form->createView()] );
    }
}
