<?php

namespace App\Controller\Frontend;

use App\Entity\Page;
use App\Repository\PopularShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(EntityManagerInterface $em, PopularShopRepository $popularShopRepository)
    {
        return $this->render('frontend/index.html.twig', [
            'popular_shops' => $popularShopRepository->findIndexItems()
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
}
