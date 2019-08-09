<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article_list")
     */
    public function list(Request $request)
    {
        $locale = $request->getLocale();

        return $this->render('frontend/article/list.html.twig', [

        ]);
    }

    /**
     * @Route("/article/{slug}", name="article_item")
     */
    public function item($slug)
    {
        return $this->render('frontend/article/item.html.twig', [

        ]);
    }
}
