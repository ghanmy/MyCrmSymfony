<?php

namespace App\Form;

use App\Entity\Activityarea;
use App\Entity\Contact;
use App\Entity\Prospect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProspectType extends AbstractType
{
    private $em;
    private $security;
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 4.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activityRepository = $this->em->getRepository(Activityarea::class);
        $activities = $activityRepository->findAll();

        $builder
            ->add('situation',ChoiceType::class,[
                'choices' => [
                    'Mr.' => 'Mr.',
                    'Ms.' => 'Ms.',
                    'Mrs.' => 'Mrs.',
                    'Dr.' => 'Dr.',
                    'Entreprise' => 'Entreprise'
                ],
                'expanded'  => false, // liste déroulante
                'multiple'  => false, // choix multiple
                'label'=>"Situation",
                //'data'=> $options['data']->getSituation() ? $options['data']->getSituation():'Mr.'
            ])
            ->add('activityArea',EntityType::class,[
                'choices' => $activities,
                'class' => Activityarea::class,
                'choice_label' => 'label',
                'placeholder' => 'Choisir une activité ...',
                'multiple'=>false
            ])
            ->add('name')
            ->add('email')
            ->add('address')
            ->add('urlsiteweb')
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
