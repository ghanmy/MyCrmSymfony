<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Calls;
use App\Entity\Contact;
use App\Entity\Prospect;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Repository\CallsRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Controller\DataTablesTrait;

/**
 * @Route("/appointment")
 */
class AppointmentController extends Controller
{
    use DataTablesTrait;
    /**
     * @Route("/", name="appointment_index", methods={"GET"})
     */
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="appointment_index", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $idProspect = $request->query->get("id_prospect");
        $appointment = new Appointment();
        if (!empty($idProspect)) {
            $prospectObject = $this->getDoctrine()->getManager()->getRepository(Prospect::class)->find($idProspect);
            $appointment->setProspect($prospectObject);
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setUser($security->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $this->addFlash('success','Rendez vous ajouté avec succès');
            $entityManager->flush();

            return $this->redirectToRoute('appointment_index');
        }

        $table = $this->createDataTable()
            ->add('appointmentid', NumberColumn::class, ['field' => 'appoint.id', 'searchable' => true])
            ->add('meetingDate', DateTimeColumn::class, ['field' => 'appoint.meetingDate','format' => 'd-m-Y', 'searchable' => true])
            ->add('meetingTime', DateTimeColumn::class, ['field' => 'appoint.meetingTime','format' => 'H:m', 'searchable' => true])
            ->add('prospect', TextColumn::class, ['field' => 'pros.name', 'searchable' => true])
            ->add('commercial', TextColumn::class, ['field' => 'com.nom', 'searchable' => true])
            ->add('call', TextColumn::class, ['field' => 'call.id', 'searchable' => true])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Calls::class,
                'query' => function (QueryBuilder $builder) use ($security) {
                    $builder
                        ->select('appoint')
                        ->from(Appointment::class, 'appoint')
                        ->where('appoint.user = :val')
                        ->setParameter('val', $security->getUser())
                        ->leftJoin('appoint.prospect', 'pros')
                        ->leftJoin('appoint.call', 'call')
                        ->leftJoin('appoint.user', 'com')

                    ;
                },
            ])
            ->add('actions', TwigColumn::class, [
                'className' => 'buttons',
                'template' => 'appointment/buttonbar.html.twig',
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('appointment/new.html.twig',['form' => $form->createView(), 'datatable' => $table]);
    }

    /**
     * @Route("/{id}", name="appointment_show", methods={"GET"})
     */
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="appointment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appointment_index', [
                'id' => $appointment->getId(),
            ]);
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appointment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Appointment $appointment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index');
    }

    public function listCallsByProspect(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $contactRepository = $em->getRepository(Calls::class);

        // Search the neighborhoods that belongs to the city with the given id as GET parameter "cityid"
        $calls = $contactRepository->createQueryBuilder("c")
            ->where("c.prospect = :prospectid")
            ->setParameter("prospectid", 8)
            ->getQuery()
            ->getResult();

        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($calls as $call){
            $responseArray[] = array(
                "id" => $call->getId(),
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
}
