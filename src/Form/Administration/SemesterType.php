<?php

namespace App\Form\Administration;

use App\Entity\Administration\Semester;
use App\Entity\Administration\Course;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        if ($options['creation']) {
            $dateFormat = $this->translator->trans('global.date.format');
            $dateAttr = [ 'data-minDate' => date($dateFormat) ];
        }

        $builder
            ->add('startDate', DateType::class, [
                'label' => 'semester.form.props.start_date.label',
                'required' => true,
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
                'attr' => $dateAttr ?? [],
            ])
            ->add('endDate', DateType::class, [
                'required' => true,
                'label' => 'semester.form.props.end_date.label',
                'widget' => 'single_text',
                'format' => $this->translator->trans('global.form.datetype.format'),
                'attr' => $dateAttr ?? [],
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semester::class,
            'creation' => false,
        ]);
    }
}
