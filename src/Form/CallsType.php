<?php

namespace App\Form;

use App\Entity\Calls;
use App\Entity\Contact;
use App\Entity\Prospect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallsType extends AbstractType
{

    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 4.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'calldate'],
                'html5' => false,
                'format'=> 'dd-mm-yyyy',

            ])
            ->add('callTime', TimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'calltime'],
                'html5' => false,

            ])
            ->add('subject',TextType::class,['required'=>true])
            ->add('nextCallDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'nextcalldate'],
                'html5' => false,
                'required'=>false,
                'format'=> 'dd-mm-yyyy',

            ])
            ->add('nextCallTime', TimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'nextcalltime'],
                'html5' => false,
                'required'=>false

            ])
            ->add('duration')
            ->add('callType', ChoiceType::class,[
                'choices' => [
                    'Entrant' => '0',
                    'Sortant' => '1'
                ],
                'expanded'  => true, // liste déroulante
                'multiple'  => false, // choix multiple
                'label_attr'=>[
                    'class'=>'radio-inline'
                 ],
                'label'=>"Type d'appel"
            ])
            ->add('comments', TextareaType::class,[  'required'=>false]);
        // Add 2 event listeners for the form
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

    }

    protected function addElements(FormInterface $form, Prospect $prospect = null) {

        $form->add('prospect', EntityType::class, [
            'data' =>$prospect,
            'class' => Prospect::class,
            'choice_label' => 'name',
            'placeholder' => 'Choisir un prospect ...',
            'multiple'=>false,
            'attr'=>array('class'=>'select2tags'),
        ]);


        $contacts = array();


        if ($prospect) {

            $contactRepository = $this->em->getRepository(Contact::class);

            $contacts = $contactRepository->createQueryBuilder("q")
                ->where("q.prospect = :prospectid")
                ->setParameter("prospectid", $prospect->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('contact', EntityType::class, array(
            'required' => false,
            'placeholder' => "D'abord choisir un prospect ...",
            'class' => Contact::class,
            'choice_label' => 'firstname',
            'choices' => $contacts,

        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $prospect = $this->em->getRepository(Prospect::class)->find($data['prospect']);

        $this->addElements($form, $prospect);
    }

    function onPreSetData(FormEvent $event) {
        $call = $event->getData();
        $form = $event->getForm();

        $prospect = $call->getProspect() ? $call->getProspect() : null;
        $this->addElements($form, $prospect);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calls::class,
            'translation_domain' => 'forms'
        ]);
    }

}
