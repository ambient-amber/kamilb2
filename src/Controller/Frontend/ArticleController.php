<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleRepository;
use App\Repository\BannerRepository;
use App\Service\DeviceDetectHelper;
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
        MetaDataHelper $metaDataHelper,
        DeviceDetectHelper $deviceDetectHelper
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url, $request->getLocale());

        if (!$category) {
            throw $this->createNotFoundException('category not found');
        }

        // 302 редирект на главную страницу, если у категории нет связи с выбранным языком
        if (empty($category->getRelevantTranslation())) {
            return $this->redirectToRoute('app_homepage');
        }

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
                'category_title' => $category->getRelevantTranslation()->getTitle(),
                'category_description' => $category->getRelevantTranslation()->getPreviewContent(),
            ]
        );

        $device = $deviceDetectHelper->getDevice();

        $banners = $bannerRepository->findFrontendBanners($device, 'article_category');

        return $this->render('frontend/article/category.html.twig', [
            'last_pagination' => $lastPagination,
            'popular_pagination' => $popularPagination,
            'category' => $category,
            'banners' => $banners,
            'meta_data' => $metaData,
            'device' => $device
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
        BannerRepository $bannerRepository,
        MetaDataHelper $metaDataHelper,
        DeviceDetectHelper $deviceDetectHelper
    ) {
        $category = $articleCategoryRepository->findActiveByUrl($category_url, $request->getLocale());

        if (!$category) {
            throw $this->createNotFoundException('category not found');
        }

        $article = $articleRepository->findActiveByUrl($article_url, $request->getLocale());

        if (!$article) {
            throw $this->createNotFoundException('article not found');
        }

        // 302 редирект на родительскую категорию, если у статьи нет связи с выбранным языком,
        // или на главную страницу, если у категории также нет нужного перевода.
        if (empty($article->getRelevantTranslation())) {
            if (empty($category->getRelevantTranslation())) {
                return $this->redirectToRoute('app_homepage');
            } else {
                return $this->redirectToRoute('article_category', ['category_url' => $category->getUrl()]);
            }
        }

        $articleRepository->increaseViewsCount($article);

        $metaData = $metaDataHelper->getMetaData(
            $request,
            [
                'title' => $article->getRelevantTranslation()->getTitle(),
                'description' => $article->getRelevantTranslation()->getPreviewContent(),
                'category_title' => $category->getRelevantTranslation()->getTitle(),
                'category_description' => $category->getRelevantTranslation()->getPreviewContent(),
            ]
        );

        $device = $deviceDetectHelper->getDevice();

        $banners = $bannerRepository->findFrontendBanners($device, 'article_category');

        return $this->render('frontend/article/item.html.twig', [
            'article' => $article,
            'category' => $category,
            'meta_data' => $metaData,
            'banners' => $banners,
            'device' => $device
        ]);
    }
}
