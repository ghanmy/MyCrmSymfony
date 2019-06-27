<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('type')
            ->add('address')
            ->add('tvacode')
            ->add('tel')
            ->add('createdat')
            ->add('contacts', CollectionType::class, [
                'mapped' => true,
                'entry_type' => ContactType::class,
                'label'=> false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
            "translation_domain" => "forms"
        ]);
    }
}
