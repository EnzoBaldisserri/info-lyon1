<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Administration\Semester;
use App\Entity\Administration\Course;

class EditSemesterType extends AbstractType
{
    private $router;
    private $translator;

    public function __construct(UrlGeneratorInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, Array $options)
    {
        $builder
            ->setAction($this->router->generate('entity_semester_new'))
            ->add('startDate', DateType::class, [
                'label' => 'semester.form.props.start_date.label',
                'required' => true,
                'attr' => ['data-minDate' => date('Y-m-d')],
            ])
            ->add('endDate', DateType::class, [
                'required' => true,
                'label' => 'semester.form.props.end_date.label',
                'attr' => ['data-minDate' => date('Y-m-d')],
            ])
            ->add('course', EntityType::class, [
                'required' => true,
                'label' => 'semester.form.props.course.label',
                'class' => Course::class,
                'choice_label' => function($course) {
                    return $this->translator->trans('course.form.choice_label', [
                        '%courseType%' => $course->getName(),
                        '%implementationYear%' => $course->getImplementationDate()->format('Y'),
                    ]);
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semester::class,
        ]);
    }
}
