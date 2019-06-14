<?php

namespace App\Form;

use App\Entity\UserSearch;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,[
                'required' => false,
                'label'=>false,
                'attr'=> [
                    'placeholder'=>"Email"
                ]
            ])
            ->add('name',TextType::class,[
                'required' => false,
                'label'=>false,
                'attr'=> [
                    'placeholder'=>"Nom"
                ]
            ])
            ->add('prenom',TextType::class,[
                'required' => false,
                'label'=>false,
                'attr'=> [
                    'placeholder'=>"Prénom"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method'=>'post',
            'csrf_protection'=>false
        ]);
    }
}
