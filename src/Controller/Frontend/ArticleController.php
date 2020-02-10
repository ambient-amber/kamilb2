<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleRepository;
use App\Repository\BannerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MetaDataHelper;

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
        ArticleCategoryRepository $articleCategoryRepository,
        BannerRepository $bannerRepository,
        MetaDataHelper $metaDataHelper
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url, $request->getLocale());

        $lastPagination = $paginator->paginate(
            $articleRepository->findLastActiveByCategory($category, $request->getLocale()),
            $request->query->getInt('last_items_page', 1),
            8,
            [
                'pageParameterName' => 'last_items_page'
            ]
        );

        $popularPagination = $paginator->paginate(
            $articleRepository->findPopularActiveByCategory($category, $request->getLocale()),
            $request->query->getInt('popular_items_page', 1),
            8,
            [
                'pageParameterName' => 'popular_items_page'
            ]
        );

        $metaData = $metaDataHelper->getMetaData(
            $request,
            [
                'title' => $category->getArticleCategoryTranslations()[0]->getTitle()
            ]
        );

        /*if ($mobileDetector->isMobile()) {
            $banners = $bannerRepository->findArticleCategoryMobileItems();
        } else if ($mobileDetector->isTablet()) {
            $banners = $bannerRepository->findArticleCategoryTabletItems();
        } else {
            $banners = $bannerRepository->findArticleCategoryDesktopItems();
        }*/

        $banners = $bannerRepository->findArticleCategoryDesktopItems();

        return $this->render('frontend/article/category.html.twig', [
            'last_pagination' => $lastPagination,
            'popular_pagination' => $popularPagination,
            'category' => $category,
            'banners' => $banners,
            'meta_data' => $metaData
        ]);
    }

    /**
     * @Route("/{category_url}/{article_url}/", name="article_item")
     */
    public function item(
        $category_url,
        $article_url,
        Request $request,
        ArticleRepository $articleRepository,
        ArticleCategoryRepository $articleCategoryRepository,
        MetaDataHelper $metaDataHelper
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url, $request->getLocale());

        $article = $articleRepository->findActiveByUrl($request->getLocale(), $article_url);

        $articleRepository->increaseViewsCount($article);

        $metaData = $metaDataHelper->getMetaData(
            $request,
            [
                'title' => $article->getArticleTranslations()[0]->getTitle(),
                'description' => $article->getArticleTranslations()[0]->getPreviewContent(),
            ]
        );

        return $this->render('frontend/article/item.html.twig', [
            'article' => $article,
            'meta_data' => $metaData
        ]);
    }
}
