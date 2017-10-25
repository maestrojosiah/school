<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Attendance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attendance = $options['data'];

        $builder
			->add('on_date', DateType::class, array(
				'widget' => 'single_text'
				)
			)
			->add('morning', CheckboxType::class, array(
			    'label'    => 'Morning',
			    'required' => false,
			))
			->add('afternoon', CheckboxType::class, array(
			    'label'    => 'Afternoon',
			    'required' => false,
			))
            ->add('student', EntityType::class, array(
                'label' => false,
                'class' => 'AppBundle:Student',
                //'choices' => $attendance->getStudent(),
                ))
            ->add('saveAndAdd', SubmitType::class, array(
                'label' => "Save and Go To Next"
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save Attendance"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Attendance::class,
        ));
    }
}