<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use AppBundle\Entity\Config;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('school_name', TextType::class)
            ->add('school_address', TextType::class)
            ->add('school_telephone', TextType::class)
			->add('results_per_page', ChoiceType::class, array(
			    'choices'  => array(
			        '10' => 10,
			        '20' => 20,
			        '30' => 30,
			        '40' => 40,
			        '50' => 50,
			    ),
			))
			->add('document_header', CKEditorType::class, array(
			    'config' => array(
			        'uiColor' => '#ffffff',
			        //...
			    ),
			))			
			->add('document_footer', CKEditorType::class, array(
			    'config' => array(
			        'uiColor' => '#ffffff',
			        //...
			    ),
			))			
            ->add('save', SubmitType::class, array(
            	'label' => "Save Settings"
            ))
        ;
    }
// ...


    public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => Config::class,
	    ));
	}

}