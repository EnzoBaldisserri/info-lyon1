<?php

namespace App\Form\Administration;

use App\Entity\Administration\Course;
use App\Entity\Administration\TeachingUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Translation\TranslatorInterface;

class CourseType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['creation']) {
            $dateFormat = $this->translator->trans('global.date.format');
            $dateAttr = [ 'data-minDate' => date($dateFormat) ];
        }

        $builder
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
            ->add('implementationDate', DateType::class, [
                'label' => 'course.form.props.implementation_date.label',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
                'attr' => $dateAttr ?? [],
            ])
            ->add('teachingUnits', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'course.form.props.teaching_units.label',
                'class' => TeachingUnit::class,
                'choice_label' => function($tu) {
                    return $tu->getFullName();
                },
                'choice_translation_domain' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'creation' => false,
        ]);
    }
}
