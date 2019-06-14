<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{ public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('firstname')
        ->add('lastname')
        ->add('email')
        ->add('sex',ChoiceType::class, [
            'choices' => [
                'Homme' => 'H',
                'Femme' => 'F'
            ],

            'multiple'  => false, // choix multiple
            'label_attr'=>[
                'class'=>'checkbox-inline'
            ]
        ])
        ->add('adress',TextType::class)
        ->add('tel1',TextType::class)
        ->add('tel2',TextType::class)
        ->add('tel3',TextType::class);
      //  ->add('createdat')
       //->add('prospect')
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
