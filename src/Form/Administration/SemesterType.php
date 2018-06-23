<?php

namespace App\Form\Administration;

use App\Entity\Administration\Semester;
use App\Entity\Administration\Course;
use App\Form\Administration\GroupEntity;
use App\Form\Type\PlainType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Translation\TranslatorInterface;

class SemesterType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $semester = $builder->getData();

        $creating = !$semester || $semester->getId() === null;
        $dateFormat = $this->translator->trans('global.date.format');

        $dateConfig = array_merge(
            [
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
            ],
            $creating ? ['attr' => [ 'data-minDate' => date($dateFormat) ]] : []
        );

        $builder
            ->add('startDate', DateType::class, array_merge(
                ['label' => 'semester.form.props.start_date.label'],
                $dateConfig
            ))
            ->add('endDate', DateType::class, array_merge(
                ['label' => 'semester.form.props.end_date.label'],
                $dateConfig
            ))
        ;

        // if creating the semester
        if ($creating) {
            $builder
                ->add('course', EntityType::class, [
                    'required' => true,
                    'label' => false,
                    'placeholder' => 'semester.form.props.course.placeholder',
                    'class' => Course::class,
                    'choice_label' => function($course) {
                        return $this->translator->trans('course.form.choice_label', [
                            '%courseType%' => $course->getName(),
                            '%implementationYear%' => $course->getImplementationDate()->format('Y'),
                        ]);
                    },
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->addOrderBy('c.implementationDate', 'DESC')
                            ->addOrderBy('c.semester', 'ASC')
                        ;
                    },
                ])
            ;
        } else {
            $course = $semester->getCourse();

            $builder
                ->add('course', PlainType::class, [
                    'data' => $this->translator->trans('course.form.choice_label', [
                        '%courseType%' => $course->getName(),
                        '%implementationYear%' => $course->getImplementationDate()->format('Y'),
                    ]),
                ])
                ->add('groups', CollectionType::class, [
                    'label' => false, // Displayed as card title
                    'entry_type' => GroupType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype_name' => '__group__',
                    'by_reference' => false,
                    'error_bubbling' => false,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semester::class,
            'creation' => false,
        ]);
    }
}
