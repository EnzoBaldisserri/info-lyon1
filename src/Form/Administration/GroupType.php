<?php

namespace App\Form\Administration;

use App\Entity\Administration\Group;
use App\Form\User\SimpleStudentType;
use App\Repository\User\StudentRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GroupType extends AbstractType
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'group.form.props.number.label',
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $group = $event->getData();
                $form = $event->getForm();

                $creating = !$group || $group->getId() === null;

                $form->add('students', CollectionType::class, [
                    'label' => false, // Displayed as collection header
                    'data' => !$creating ? $this->studentRepository->findInGroup($group) : [],
                    'entry_type' => SimpleStudentType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype_name' => '__student__',
                    'mapped' => false,
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
