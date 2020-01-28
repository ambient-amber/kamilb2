<?php

namespace App\Controller\Backend;

use App\Entity\Banner;
use App\Form\BannerType;
use App\Repository\BannerRepository;
use App\Service\UploadHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/banner")
 */
class BannerController extends AbstractController
{
    /**
     * @Route("/", name="banner_index", methods={"GET"})
     */
    public function index(BannerRepository $bannerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $bannerRepository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('backend/banner/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="banner_new", methods={"GET","POST"})
     */
    public function new(Request $request, UploadHelper $uploadHelper): Response
    {
        $banner = new Banner();
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $plainImage = $request->files->get('banner')['plainImage'];
            $uploadedFile = $uploadHelper->uploadHashFile($plainImage);

            $banner->setImageHash($uploadedFile['hash_name']);
            $banner->setImageName($uploadedFile['original_name']);

            $entityManager->persist($banner);
            $entityManager->flush();

            return $this->redirectToRoute('banner_index');
        }

        return $this->render('backend/banner/new.html.twig', [
            'banner' => $banner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banner_show", methods={"GET"})
     */
    public function show(Banner $banner): Response
    {
        return $this->render('backend/banner/show.html.twig', [
            'banner' => $banner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="banner_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Banner $banner, UploadHelper $uploadHelper): Response
    {
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainImage = $request->files->get('article')['plainImage'];

            if (!empty($plainImage)) {
                $uploadedFile = $uploadHelper->uploadHashFile($plainImage);

                if (!empty($banner->getImageHash())) {
                    $uploadHelper->unloadHashFile($banner->getImageHash());
                }

                $banner->setImageHash($uploadedFile['hash_name']);
                $banner->setImageName($uploadedFile['original_name']);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('banner_index');
        }

        return $this->render('backend/banner/edit.html.twig', [
            'banner' => $banner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banner_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Banner $banner, UploadHelper $uploadHelper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banner->getId(), $request->request->get('_token'))) {
            $uploadHelper->unloadHashFile($banner->getImageHash());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('banner_index');
    }

    /**
     * @Route("/{id}/pub_toggle", name="banner_pub_toggle", methods={"GET","POST"})
     */
    public function pub(Request $request, Banner $banner): JsonResponse
    {
        $banner->setPub(!$banner->getPub());
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
