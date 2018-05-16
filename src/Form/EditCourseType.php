<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Administration\Course;

class EditCourseType extends AbstractType
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, Array $options)
    {
        $builder
            ->setAction($this->router->generate('entity_course_new'))
            ->add('implementationDate', DateType::class, [
                'label' => 'course.form.props.implementation_date.label',
                'required' => true,
                'attr' => ['data-minDate' => date('Y-m-d')],
            ])
            ->add('semester', ChoiceType::class, [
                'label' => 'course.form.props.semester.label',
                'required' => true,
                'choices' => [
                    'S1' => 1,
                    'S2' => 2,
                    'S3' => 3,
                    'S4' => 4,
                ],
                'choice_translation_domain' => false,
                'placeholder' => 'course.form.props.semester.placeholder'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
