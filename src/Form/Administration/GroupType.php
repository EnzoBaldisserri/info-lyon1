<?php

namespace App\Form\Administration;

use App\Entity\Administration\Group;
use App\Entity\User\Student;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', NumberType::class, [
                'required' => true,
                'label' => 'group.form.props.number.label',
                'scale' => 0,
            ])
            ->add('students', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'group.form.props.students.label',
                'class' => Student::class,
                'choice_label' => function($student) {
                    return $student->getFullName();
                },
                'choice_translation_domain' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'semester' => null,
        ]);
    }
}
