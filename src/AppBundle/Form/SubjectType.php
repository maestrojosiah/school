<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject_title', ChoiceType::class, array(
                'choices'  => array(
                    'Maths' => 'Maths',
                    'English(Language+Composition)' => 'English|parent|Language_Composition',
                    'English' => 'English',
                    'Kiswahili(Lugha+Insha)' => 'Kiswahili|parent|Lugha_Insha',
                    'Kiswahili' => 'Kiswahili',
                    'Science' => 'Science',
                    'Social Studies(SS+CRE)' => 'Social/CRE|parent|Social-Studies_CRE',
                    'Social Studies' => 'Social-Studies',
                    'Music' => 'Music',
                    'Art and Craft' => 'Art-and-Craft',
                    'Home Science' => 'Home-Science',
                    'Other' => 'Other'
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Subject::class,
        ));
    }
}