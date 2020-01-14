<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\UploadHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $repository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder, // query NOT result
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('backend/article/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request, UploadHelper $uploadHelper): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $plainImage = $request->files->get('article')['plainImage'];
            $uploadedFile = $uploadHelper->uploadHashFile($plainImage);

            $article->setImageHash($uploadedFile['hash_name']);
            $article->setImageName($uploadedFile['original_name']);

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('backend/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('backend/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article, UploadHelper $uploadHelper): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainImage = $request->files->get('article')['plainImage'];

            if (!empty($plainImage)) {
                $uploadedFile = $uploadHelper->uploadHashFile($plainImage);

                $uploadHelper->unloadHashFile($article->getImageHash());

                $article->setImageHash($uploadedFile['hash_name']);
                $article->setImageName($uploadedFile['original_name']);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('backend/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article, UploadHelper $uploadHelper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $uploadHelper->unloadHashFile($article->getImageHash());

            foreach ($article->getArticleTranslations() as $translation) {
                $uploadHelper->unloadHashFilesFromContent($translation->getContent());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}/pub_toggle", name="article_pub_toggle", methods={"GET","POST"})
     */
    public function pub(Request $request, Article $article): JsonResponse
    {
        $article->setPub(!$article->getPub());
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
