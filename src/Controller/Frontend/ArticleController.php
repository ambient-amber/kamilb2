<?php

namespace App\Controller\Frontend;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Frontend\FrontController;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     */
    public function list(Request $request, PaginatorInterface $paginator, ArticleRepository $repository)
    {
        $queryBuilder = $repository->findActiveItems($request->getLocale());

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('frontend/article/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/{url}", name="article_item")
     */
    public function item($url, ArticleRepository $repository, Request $request)
    {
        $item = $repository->findByUrl($request->getLocale(), $url);

        return $this->render('frontend/article/item.html.twig', [
            'article' => array_shift($item)
        ]);
    }
}
