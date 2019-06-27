<?php

namespace App\Controller;

use App\Entity\Calls;
use App\Entity\Prospect;
use App\Form\CallsType;
use App\Repository\CallsRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\QueryBuilder;
use http\Client\Curl\User;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;

/**
 * @Route("/calls")
 */
class CallsController extends Controller
{
    /**
     * @Route("/", name="calls_index", metcalls_newhods={"GET"})
     */
    /*  public function index(CallsRepository $callsRepository,Security $security): Response
      {
          return $this->render('calls/index.html.twig', [
              'calls' => $callsRepository->findByUser($security->getUser()),
          ]);
      }
  */
    /**
     * @Route("/index", name="calls_index", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security, CallsRepository $callsRepository): Response
    {
        $idProspect = $request->query->get("id_prospect");
        $call = new Calls();
        if (!empty($idProspect)) {
            $prospectObject = $this->getDoctrine()->getManager()->getRepository(Prospect::class)->find($idProspect);
            $call->setProspect($prospectObject);
        }

        $form = $this->createForm(CallsType::class, $call);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $call->setUser($security->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($call);
            $this->addFlash('success','Appel ajouté avec succès');
            $entityManager->flush();

            return $this->redirectToRoute('calls_index');
        }
        $table = $this->createDataTable()
            ->add('callid', NumberColumn::class, ['field' => 'e.id', 'searchable' => true])
            ->add('date', DateTimeColumn::class, ['format' => 'd-m-Y', 'searchable' => true])
            ->add('nextCallDate', DateTimeColumn::class, ['format' => 'd-m-Y', 'searchable' => false])
            ->add('prname', TextColumn::class, ['field' => 'p.name', 'searchable' => true])
            ->add('ptel', TextColumn::class, ['field' => 'p.tel', 'searchable' => true])
            ->add('contact', TextColumn::class, ['field' => 'c.firstname', 'searchable' => true])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Calls::class,
                'query' => function (QueryBuilder $builder) use ($security) {
                    $builder
                        ->select('e')
                        ->from(Calls::class, 'e')
                        ->where('e.user = :val')
                        ->setParameter('val', $security->getUser())
                        ->leftJoin('e.prospect', 'p')
                        ->leftJoin('e.contact', 'c');
                },

            ])
            ->add('actions', TwigColumn::class, [
                'className' => 'buttons',
                'template' => 'calls/buttonbar.html.twig',
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('calls/new.html.twig', ['form' => $form->createView(), 'datatable' => $table]);
    }

    /**
     * @Route("/{id}", name="calls_show", methods={"GET"})
     */
    public function show(Calls $call): Response
    {
        return $this->render('calls/show.html.twig', [
            'call' => $call,
        ]);
    }


    use DataTablesTrait;

    /**
     * @Route("/{id}/edit", name="calls_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calls $call, Security $security): Response
    {
        $callid= $call->getId();
        //dd($request->attributes->get('id'));
        $table = $this->createDataTable()
            ->add('callid', NumberColumn::class, ['field' => 'e.id', 'searchable' => true])
            ->add('date', DateTimeColumn::class, ['format' => 'd-m-Y', 'searchable' => true])
            ->add('nextCallDate', DateTimeColumn::class, ['format' => 'd-m-Y', 'searchable' => false])
            ->add('prname', TextColumn::class, ['field' => 'p.name', 'searchable' => true])
            ->add('contact', TextColumn::class, ['field' => 'c.firstname', 'searchable' => true])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Calls::class,
                'query' => function (QueryBuilder $builder) use ($security) {
                    $builder
                        ->select('e')
                        ->from(Calls::class, 'e')
                        ->where('e.user = :val')
                        ->setParameter('val', $security->getUser())
                        ->leftJoin('e.prospect', 'p')
                        ->leftJoin('e.contact', 'c');
                },

            ])
            ->add('actions', TwigColumn::class, [
                'className' => 'buttons',
                'template' => 'calls/buttonbar.html.twig',
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $form = $this->createForm(CallsType::class, $call);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Appel modifié avec succès');
        }
        return $this->render('calls/edit.html.twig', ['callid' => $callid,'form' => $form->createView(), 'datatable' => $table]);
    }

    /**
     * @Route("/{id}", name="calls_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Calls $call): Response
    {
        if ($this->isCsrfTokenValid('delete' . $call->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($call);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calls_index');
    }
}
