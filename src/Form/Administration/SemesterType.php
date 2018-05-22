<?php

namespace App\Form\Administration;

use App\Entity\Administration\Semester;
use App\Entity\Administration\Course;
use App\Form\Administration\GroupEntity;
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
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'semester.form.props.start_date.label',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
            ])
            ->add('endDate', DateType::class, [
                'required' => true,
                'label' => 'semester.form.props.end_date.label',
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
            ])
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

        $dateFormat = $this->translator->trans('global.date.format');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($dateFormat) {
                $semester = $event->getData();
                $form = $event->getForm();

                // if creating the semester
                if (!$semester || null === $semester->getId()) {
                    // Add min date to start date
                    $config = $form->get('startDate')->getConfig();

                    $form->add('startDate', DateType::class, array_replace(
                        $config->getOptions(),
                        [
                            'attr' => [ 'data-minDate' => date($dateFormat) ]
                        ]
                    ));

                    // Add min date to end date
                    $config = $form->get('endDate')->getConfig();

                    $form->add('endDate', DateType::class, array_replace(
                        $config->getOptions(),
                        [
                            'attr' => [ 'data-minDate' => date($dateFormat) ]
                        ]
                    ));
                } else {
                    // Add readonly to course
                    $config = $form->get('course')->getConfig();

                    $form->add('course', EntityType::class, array_replace(
                        $config->getOptions(),
                        [
                            'attr' => ['readonly' => 'readonly'],
                        ]
                    ));

                    // Add groups
                    $form->add('groups', CollectionType::class, [
                        'label' => false,
                        'entry_type' => GroupType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semester::class,
            'creation' => false,
        ]);
    }
}
