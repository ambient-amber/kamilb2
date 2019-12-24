<?php

namespace App\Controller\Backend;

use App\Entity\ContentBlock;
use App\Form\ContentBlockType;
use App\Repository\ContentBlockRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/content_block")
 */
class ContentBlockController extends AbstractController
{
    /**
     * @Route("/", name="content_block_index", methods={"GET"})
     */
    public function index(ContentBlockRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $repository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder, // query NOT result
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('backend/content_block/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="content_block_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contentBlock = new ContentBlock();
        $form = $this->createForm(ContentBlockType::class, $contentBlock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contentBlock);
            $entityManager->flush();

            return $this->redirectToRoute('content_block_index');
        }

        return $this->render('backend/content_block/new.html.twig', [
            'content_block' => $contentBlock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_block_show", methods={"GET"})
     */
    public function show(ContentBlock $contentBlock): Response
    {
        return $this->render('backend/content_block/show.html.twig', [
            'content_block' => $contentBlock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_block_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContentBlock $contentBlock): Response
    {
        $form = $this->createForm(ContentBlockType::class, $contentBlock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_block_index');
        }

        return $this->render('backend/content_block/edit.html.twig', [
            'content_block' => $contentBlock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_block_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContentBlock $contentBlock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contentBlock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contentBlock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_block_index');
    }
}
