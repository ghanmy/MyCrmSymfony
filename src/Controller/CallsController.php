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
            $entityManager->flush();

            return $this->redirectToRoute('calls_index');
        }
        $calls = $callsRepository->findByUser($security->getUser());
        return $this->render('calls/new.html.twig', [
            'call' => $call,
            'calls' => $calls,
            'form' => $form->createView(),
        ]);
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

    /**
     * @Route("/{id}/edit", name="calls_edit", methods={"GET","POST"})
     */
   /* public function edit(Request $request, Calls $call, CallsRepository $callsRepository, Security $security): Response
    {
        $form = $this->createForm(CallsType::class, $call);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calls_index', [
                // 'id' => $call->getId(),
            ]);
        }

        return $this->render('calls/edit.html.twig', [
            'call' => $call,
            'calls' => $callsRepository->findByUser($security->getUser()),
            'form' => $form->createView(),
        ]);
    }*/
    use DataTablesTrait;

    public function edit(Request $request, Calls $call, CallsRepository $callsRepository, Security $security): Response
    {
        $table = $this->createDataTable()
            ->add('id', NumberColumn::class)
            ->add('p', TextColumn::class, ['field' => 'p.name'])
            ->add('comments', TextColumn::class)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Calls::class,
                'query' => function (QueryBuilder $builder) use ($security) {
                    $builder
                        ->select('e')
                        ->from(Calls::class, 'e')
                        ->where('e.user = :val')
                        ->setParameter('val', $security->getUser())
                        ->leftJoin('e.prospect', 'c')
                    ;
                },

            ])
            ->handleRequest($request);
        $form = $this->createForm(CallsType::class, $call);
        $form->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('calls/edit.html.twig',['form'=>$form->createView(),'datatable' => $table]);
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
