<?php

namespace App\Form\User;

use App\Entity\User\Student;
use App\Form\Type\PlainType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SimpleStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $student = $event->getData();

                $form
                    ->add('id', HiddenType::class, [
                        'data' => $student ? $student->getId() : null,
                        'mapped' => false,
                    ])
                    ->add('fullname', PlainType::class, [
                        'data' => $student ? $student->getFullName() : '',
                        'mapped' => false,
                    ])
                ;
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Students aren't really represented by this form
            // It's just a convenient way to display them
            'data_class' => Student::class,
        ]);
    }
}
