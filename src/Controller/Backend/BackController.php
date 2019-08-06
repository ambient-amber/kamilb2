<?php

namespace App\Controller\Backend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function homepage()
    {
        return $this->render('backend/base.html.twig', [
        ]);
    }
}
