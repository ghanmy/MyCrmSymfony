<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Calls;
use App\Entity\Contact;
use App\Entity\Prospect;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class AppointmentType extends AbstractType
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

        $userRepository = $this->em->getRepository(User::class);
        $commercials = $userRepository->findByRole('ROLE_COMMERCIAL');

        $builder
            ->add('meetingDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'meetingdate'],
                'html5' => false,
                'format'=> 'dd-mm-yyyy',

            ])
            ->add('meetingTime', TimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'meetingtime'],
                'html5' => false,

            ])
            ->add('meetingPlace')
            ->add('commercial', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'choices' => $commercials,
                'placeholder' => 'Choisir un commercial ...',
                'multiple' => false
            ]);

        $prospectRepository = $this->em->getRepository(Prospect::class);
        $ROLES = $this->security->getUser()->getRoles();

        if (in_array("ROLE_ADMIN", $ROLES))
            $prospects = $prospectRepository->findAll();
        else
            $prospects = $prospectRepository->findBy(array('user' => $this->security->getUser()));

        if ($prospects) {
            $builder->add('prospect', EntityType::class, [
                'choices' => $prospects,
                'class' => Prospect::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un prospect ...',
                'multiple' => false,
                'attr' => array('class' => 'select2tags'),
                'required' => true,
            ]);
        } else {
            $builder->addError(new FormError("Pour créer un rendez vous devez creér au moins un prospect"));
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'));
    }

    function onPresetData(FormEvent $event)
    {
        $form = $event->getForm();
        $rdv = $event->getData();
        if (!empty($rdv->getProspect()))
            $calls = $this->em->getRepository(Calls::class)->findBy(array("prospect" => $rdv->getProspect()));
        else
            $calls = array();

        $form->add('call', EntityType::class, array(
            'choices' => $calls,
            'required' => false,
            'placeholder' => "D'abord choisir un appel ...",
            'class' => Calls::class,
            'choice_label' => 'id',
            'required'=>false,
            'empty_data' => NULL,
        ));
    }

    public function onPreSubmitData(FormEvent $event)
    {
        $form = $event->getForm();
        $rdv = $event->getData();

        $calls = array();
        $calls=$this->em->getRepository(Prospect::class)->find($rdv['prospect']);
        $form->add('call', EntityType::class, array(
            'data' =>$calls,
            'required' => false,
            'placeholder' => "D'abord choisir un appel ...",
            'class' => Calls::class,
            'choice_label' => 'id',
            'required'=>false,
            'empty_data' => NULL,
        ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'translation_domain' => 'forms'
        ]);
    }
}
