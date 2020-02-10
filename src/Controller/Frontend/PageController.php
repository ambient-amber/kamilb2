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
        $item = $pageRepository->findActiveByUrl($request->getLocale(), $url);

        $metaData = $metaDataHelper->getMetaData(
            $request,
            [
                'title' => $item->getPageTranslations()[0]->getTitle(),
                'description' => $item->getPageTranslations()[0]->getTitle(),
            ]
        );

        return $this->render('frontend/page/item.html.twig', [
            'page' => $item,
            'meta_data' => $metaData,
        ]);
    }
}
