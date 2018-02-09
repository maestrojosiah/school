<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $student = $options['data'];
        $user = $student->getUser();        
        $builder
            ->add('f_name', TextType::class, array('label' => false ) )
            ->add('l_name', TextType::class, array('label' => false ) )
            ->add('classs', EntityType::class, array(
                'label' => false,
                'class' => 'AppBundle:Classs',
                // 'choice_label' => 'title'
                'choices' => $user->getClassses(),
                ))
            ->add('contact', TextType::class, array('label' => false ) )
            ->add('age', TextType::class, array('label' => false ) )
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Male' => 'male',
                    'Female' => 'female',
                ),
            ))
            ->add('saveAndAdd', SubmitType::class, array(
                'label' => "Save and Add More"
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save Student"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Student::class,
        ));
    }
}