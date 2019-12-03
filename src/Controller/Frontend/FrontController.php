<?php

namespace App\Controller\Frontend;

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
        return $this->render('frontend/base.html.twig', [
            'popular_shops' => $popularShopRepository->findIndexItems()
        ]);
    }
}
