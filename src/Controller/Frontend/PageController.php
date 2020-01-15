<?php

namespace App\Controller\Frontend;

use App\Entity\Page;
use App\Repository\PageRepository;
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
    public function item($url, PageRepository $pageRepository, Request $request): Response
    {
        $item = $pageRepository->findActiveByUrl($request->getLocale(), $url);

        return $this->render('frontend/page/item.html.twig', [
            'page' => $item
        ]);
    }
}
