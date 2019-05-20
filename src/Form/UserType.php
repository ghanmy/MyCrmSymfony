<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel1')
            ->add('tel2')
            ->add('pays')
            ->add('password',PasswordType::class)
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Téléopérateur' => 'ROLE_TELEOPERATOR',
                    'Commercial' => 'ROLE_COMMERCIAL',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded'  => true, // liste déroulante
                'multiple'  => true, // choix multiple
                'label_attr'=>[
            		'class'=>'checkbox-inline'
            	]
            ])
            ->add('isActive')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "translation_domain" => "forms"
        ]);
    }
}
