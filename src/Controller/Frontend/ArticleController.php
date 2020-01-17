<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{category_url}/", name="article_category")
     */
    public function category(
        $category_url,
        Request $request,
        PaginatorInterface $paginator,
        ArticleRepository $articleRepository,
        ArticleCategoryRepository $articleCategoryRepository
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url);
        $queryBuilder = $articleRepository->findActiveByCategory($category, $request->getLocale());

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('frontend/article/category.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/{category_url}/{article_url}/", name="article_item")
     */
    public function item(
        $category_url,
        $article_url,
        ArticleRepository $articleRepository,
        ArticleCategoryRepository $articleCategoryRepository,
        Request $request
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url);
        $article = $articleRepository->findActiveByUrl($request->getLocale(), $article_url);

        $articleRepository->increaseViewsCount($article);

        return $this->render('frontend/article/item.html.twig', [
            'article' => $article
        ]);
    }
}
