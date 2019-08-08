<?php

namespace App\Controller\Backend;

use App\Entity\PopularShop;
use App\Form\PopularShopType;
use App\Repository\PopularShopRepository;
use App\Service\UploadHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/popular/shop")
 */
class PopularShopController extends AbstractController
{
    /**
     * @Route("/", name="popular_shop_index", methods={"GET"})
     */
    public function index(PopularShopRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $repository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder, // query NOT result
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('backend/popular_shop/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="popular_shop_new", methods={"GET","POST"})
     */
    public function new(Request $request, UploadHelper $uploadHelper): Response
    {
        $popularShop = new PopularShop();
        $form = $this->createForm(PopularShopType::class, $popularShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $plainImage = $request->files->get('popular_shop')['plainImage'];
            $uploadedFile = $uploadHelper->uploadHashFile($plainImage);

            $popularShop->setImageHash($uploadedFile['hash_name']);
            $popularShop->setImageName($uploadedFile['original_name']);

            $entityManager->persist($popularShop);
            $entityManager->flush();

            return $this->redirectToRoute('popular_shop_index');
        }

        return $this->render('backend/popular_shop/new.html.twig', [
            'popular_shop' => $popularShop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="popular_shop_show", methods={"GET"})
     */
    public function show(PopularShop $popularShop): Response
    {
        return $this->render('backend/popular_shop/show.html.twig', [
            'popular_shop' => $popularShop,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="popular_shop_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PopularShop $popularShop, UploadHelper $uploadHelper): Response
    {
        $form = $this->createForm(PopularShopType::class, $popularShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainImage = $request->files->get('popular_shop')['plainImage'];

            if (!empty($plainImage)) {
                $uploadedFile = $uploadHelper->uploadHashFile($plainImage);
                $popularShop->setImageHash($uploadedFile['hash_name']);
                $popularShop->setImageName($uploadedFile['original_name']);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('popular_shop_index');
        }

        return $this->render('backend/popular_shop/edit.html.twig', [
            'popular_shop' => $popularShop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="popular_shop_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PopularShop $popularShop): Response
    {
        if ($this->isCsrfTokenValid('delete'.$popularShop->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($popularShop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('popular_shop_index');
    }
}
