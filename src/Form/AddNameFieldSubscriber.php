<?php

namespace App\Form;

use App\Entity\Calls;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;

class AddNameFieldSubscriber implements EventSubscriberInterface
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
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $rdv = $event->getData();
dump($form);
        if (!empty($rdv->getProspect())){
            dump($rdv->getProspect());
            $calls = $this->em->getRepository(Calls::class)->findBy(array("prospect" => $rdv->getProspect()));
        }
        else
            $calls = array();
        //$calls = $prospects[0]->getCalls();
        dump($calls);


        $form->add('call', EntityType::class, array(
            'choices' => $calls,
            'required' => false,
            'placeholder' => "D'abord choisir un appel ...",
            'class' => Calls::class,
            'choice_label' => 'id',

        ));
    }
}