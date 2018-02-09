<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Classs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClasssType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('class_number', ChoiceType::class, array(
                'choices' => array(
                    '1' => 'One',
                    '2' => 'Two',
                    '3' => 'Three',
                    '4' => 'Four',
                    '5' => 'Five',
                    '6' => 'Six',
                    '7' => 'Seven',
                    '8' => 'Eight',
                ),
                'label' => false,
            ))
            ->add('class_teacher', TextType::class, array('label' => false))
            ->add('saveAndAdd', SubmitType::class, array(
                'label' => "Save and Add More"
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save Class"
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Classs::class,
        ));
    }
}