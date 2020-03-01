<?php

namespace App\Controller\Frontend;

use App\Entity\ArticleCategory;
use App\Entity\Language;
use App\Entity\Page;
use App\Repository\ArticleRepository;
use App\Repository\BannerRepository;
use App\Service\MetaDataHelper;
use App\Service\DeviceDetectHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(
        ArticleRepository $articleRepository,
        BannerRepository $bannerRepository,
        PaginatorInterface $paginator,
        Request $request,
        MetaDataHelper $metaDataHelper,
        DeviceDetectHelper $deviceDetectHelper
    ) {
        $lastPagination = $paginator->paginate(
            $articleRepository->findLastActive($request->getLocale()),
            $request->query->getInt('last_articles_page', 1),
            8,
            [
                'pageParameterName' => 'last_articles_page'
            ]
        );

        $popularPagination = $paginator->paginate(
            $articleRepository->findPopularActive($request->getLocale()),
            $request->query->getInt('popular_articles_page', 1),
            8,
            [
                'pageParameterName' => 'popular_articles_page'
            ]
        );

        $metaData = $metaDataHelper->getMetaData($request);

        $device = $deviceDetectHelper->getDevice();

        $banners = $bannerRepository->findFrontendBanners($device, 'index');

        return $this->render('frontend/index.html.twig', [
            'last_pagination' => $lastPagination,
            'popular_pagination' => $popularPagination,
            'banners' => $banners,
            'meta_data' => $metaData,
            'device' => $device,
        ]);
    }

    public function footerPages($locale)
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $footerPages = $pageRepository->findFooterItems($locale);

        return $this->render('frontend/_footer_pages.html.twig', [
            'footer_pages' => $footerPages
        ]);
    }

    public function headerLanguages($url, $locale)
    {
        $pageLanguages = [];
        $url = preg_replace('/^\/' . $locale . '/', '', $url);
        $defaultLocale = $this->getParameter('default_locale');

        $em = $this->getDoctrine()->getManager();
        $languageRepository = $em->getRepository(Language::class);
        $languages = $languageRepository->findAll();

        foreach ($languages as $language) {
            if ($language->getTextId() != $defaultLocale) {
                $languageUrl = '/' . $language->getTextId() . $url;
            } else {
                $languageUrl = $url;
            }

            $pageLanguages[] = [
                'text_id_title' => strtoupper($language->getTextId()),
                'selected' => ($language->getTextId() == $locale),
                'url' => $languageUrl
            ];
        }

        return $this->render('frontend/_header_languages.html.twig', [
            'page_languages' => $pageLanguages
        ]);
    }

    public function headerArticleCategories($url, $locale)
    {
        $resultCategories = [];

        $em = $this->getDoctrine()->getManager();
        $articleCategoryRepository = $em->getRepository(ArticleCategory::class);
        $categories = $articleCategoryRepository->findActive($locale);

        foreach ($categories as $category) {
            $selected = preg_match('/\/article\/' . $category->getUrl() . '\//', $url);
            $title = '';

            foreach ($category->getArticleCategoryTranslations() as $translation) {
                if ($translation->getLanguage()->getTextId() == $locale) {
                    $title = $translation->getTitle();
                }
            }

            $resultCategories[] = [
                'url' => $category->getUrl(),
                'title' => $title,
                'selected' => $selected
            ];
        }

        return $this->render('frontend/_header_article_categories.html.twig', [
            'article_categories' => $resultCategories
        ]);
    }
}
