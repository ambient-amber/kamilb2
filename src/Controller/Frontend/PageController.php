<?php

namespace App\Controller\Frontend;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/{url}/", name="page_show", methods={"GET"})
     */
    public function item(Page $page): Response
    {
        return $this->render('frontend/page/item.html.twig', [
            'page' => $page,
        ]);
    }
}
