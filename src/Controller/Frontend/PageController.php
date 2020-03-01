<?php

namespace App\Controller\Frontend;

use App\Repository\PageRepository;
use App\Service\MetaDataHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/{url}/", name="page_item", methods={"GET"})
     */
    public function item(
        $url,
        PageRepository $pageRepository,
        Request $request,
        MetaDataHelper $metaDataHelper
    ) {
        $page = $pageRepository->findActiveByUrl($url, $request->getLocale());

        if (!$page) {
            throw $this->createNotFoundException('page not found');
        }

        // 302 редирект на главную страницу, если у страницы нет нужного перевода.
        if (empty($page->getRelevantTranslation())) {
            return $this->redirectToRoute('app_homepage');
        }

        $metaData = $metaDataHelper->getMetaData(
            $request,
            [
                'title' => $page->getPageTranslations()[0]->getTitle(),
                'description' => $page->getPageTranslations()[0]->getTitle(),
            ]
        );

        return $this->render('frontend/page/item.html.twig', [
            'page' => $page,
            'meta_data' => $metaData,
        ]);
    }
}
