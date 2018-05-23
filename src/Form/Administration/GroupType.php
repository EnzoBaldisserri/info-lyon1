<?php

namespace App\Form\Administration;

use App\Entity\Administration\Group;
use App\Entity\User\Student;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'group.form.props.number.label',
            ])
            ->add('students', EntityType::class, [
                'label' => false, // Displayed as collection header
                'multiple' => true,
                'class' => Student::class,
                'choice_label' => function($student) {
                    return $student->getFullName();
                },
                'query_builder' => function(EntityRepository $er) {
                    $er->createQueryBuilder('s')
                        ->addOrderBy('s.surname', 'ASC')
                        ->addOrderBy('s.firstname', 'ASC')
                    ;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
