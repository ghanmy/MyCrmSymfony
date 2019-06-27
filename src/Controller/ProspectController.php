<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Entity\Contact;
use App\Entity\ProspectSearch;
use App\Form\ProspectSearchType;
use App\Form\ProspectType;
use App\Repository\ProspectsRepository;
use App\Service\PaginatorStep;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/prospect")
 */
class ProspectController extends AbstractController
{
    /**
     * @Route("/", name="prospect_index", methods={"GET","POST"})
     */
    public function index(PaginatorInterface $paginator,ProspectsRepository $prospectRepository,Request $request,PaginatorStep $paginatorstep): Response
    {
        $search = new ProspectSearch();
        $form = $this->createForm(ProspectSearchType::class,$search);
        $form->handleRequest($request);
        $pagination = $paginator->paginate(
            $prospectRepository->findAllQuery($search), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $paginatorstep->getStep() /*limit per page*/
        );
        return $this->render('prospect/index.html.twig', [
            'prospects' => $pagination,
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/new", name="prospect_new", methods={"GET","POST"})
     */
    public function new(Request $request,Security $security): Response
    {
        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect)->remove('createdat');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prospect->setUser($security->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);
            $entityManager->flush();

            return $this->redirectToRoute('prospect_index');
        }

        return $this->render('prospect/new.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prospect_show", methods={"GET"})
     */
    public function show(Prospect $prospect): Response
    {
        return $this->render('prospect/show.html.twig', [
            'prospect' => $prospect,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prospect_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prospect $prospect): Response
    {
        $form = $this->createForm(ProspectType::class, $prospect)->remove('createdat');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'We edited a prospect with id ' . $prospect->getId());

            return $this->redirectToRoute('prospect_index', [
                'id' => $prospect->getId(),
            ]);
        }

        return $this->render('prospect/edit.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="prospect_delete", methods={"DELETE","GET"})
     */
    public function delete(Request $request, Prospect $prospect): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$prospect->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prospect);
            $entityManager->flush();
      //  }

        return $this->redirectToRoute('prospect_index');
    }
    /**
     * Returns a JSON string with the contact of the Prospect with the providen id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listContactsByProspect(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $contactRepository = $em->getRepository(Contact::class);

        // Search the neighborhoods that belongs to the city with the given id as GET parameter "cityid"
        $contacts = $contactRepository->createQueryBuilder("q")
            ->where("q.prospect = :prospectid")
            ->setParameter("prospectid", $request->query->get("prospectid"))
            ->getQuery()
            ->getResult();
//dd($contacts );
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($contacts as $contact){
            $responseArray[] = array(
                "id" => $contact->getId(),
                "name" => $contact->getFirstname()
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
}
