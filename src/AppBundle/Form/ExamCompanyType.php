<?php 

namespace AppBundle\Form;

use AppBundle\Entity\ExamCompany;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExamCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company_name', TextType::class, array('label' => false ))
            ->add('saveAndAdd', SubmitType::class, array(
                'label' => "Save and Add More"
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save ExamCompany"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ExamCompany::class,
        ));
    }
}