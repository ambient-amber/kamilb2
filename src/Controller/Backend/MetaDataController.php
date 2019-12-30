<?php

namespace App\Controller\Backend;

use App\Entity\MetaData;
use App\Form\MetaDataType;
use App\Repository\MetaDataRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/meta-data")
 */
class MetaDataController extends AbstractController
{
    /**
     * @Route("/", name="meta_data_index", methods={"GET"})
     */
    public function index(MetaDataRepository $metaDataRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $metaDataRepository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder, // query NOT result
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('backend/meta_data/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="meta_data_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $metaData = new MetaData();
        $form = $this->createForm(MetaDataType::class, $metaData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($metaData);
            $entityManager->flush();

            return $this->redirectToRoute('meta_data_index');
        }

        return $this->render('backend/meta_data/new.html.twig', [
            'meta_data' => $metaData,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meta_data_show", methods={"GET"})
     */
    public function show(MetaData $metaData): Response
    {
        return $this->render('backend/meta_data/show.html.twig', [
            'meta_data' => $metaData,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="meta_data_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MetaData $metaData): Response
    {
        $form = $this->createForm(MetaDataType::class, $metaData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meta_data_index');
        }

        return $this->render('backend/meta_data/edit.html.twig', [
            'meta_data' => $metaData,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meta_data_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MetaData $metaData): Response
    {
        if ($this->isCsrfTokenValid('delete'.$metaData->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($metaData);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meta_data_index');
    }

    /**
     * @Route("/{id}/pub_toggle", name="meta_data_pub_toggle", methods={"GET","POST"})
     */
    public function pub(MetaData $metaData): JsonResponse
    {
        $metaData->setPub(!$metaData->getPub());
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
