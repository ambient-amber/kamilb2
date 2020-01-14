<?php

namespace App\Controller\Backend;

use App\Entity\ArticleCategory;
use App\Form\ArticleCategoryType;
use App\Repository\ArticleCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article/category")
 */
class ArticleCategoryController extends AbstractController
{
    /**
     * @Route("/", name="article_category_index", methods={"GET"})
     */
    public function index(ArticleCategoryRepository $articleCategoryRepository): Response
    {
        return $this->render('backend/article_category/index.html.twig', [
            'article_categories' => $articleCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $articleCategory = new ArticleCategory();
        $form = $this->createForm(ArticleCategoryType::class, $articleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articleCategory);
            $entityManager->flush();

            return $this->redirectToRoute('article_category_index');
        }

        return $this->render('backend/article_category/new.html.twig', [
            'article_category' => $articleCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_category_show", methods={"GET"})
     */
    public function show(ArticleCategory $articleCategory): Response
    {
        return $this->render('backend/article_category/show.html.twig', [
            'article_category' => $articleCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ArticleCategory $articleCategory): Response
    {
        $form = $this->createForm(ArticleCategoryType::class, $articleCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_category_index');
        }

        return $this->render('backend/article_category/edit.html.twig', [
            'article_category' => $articleCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ArticleCategory $articleCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($articleCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_category_index');
    }

    /**
     * @Route("/{id}/pub_toggle", name="article_category_pub_toggle", methods={"GET","POST"})
     */
    public function pub(Request $request, ArticleCategory $articleCategory): JsonResponse
    {
        $articleCategory->setPub(!$articleCategory->getPub());
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
