<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meetingDate')
            ->add('meetingTime')
            ->add('meetingPlace')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('prospect')
            ->add('call')
            ->add('commercial')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
