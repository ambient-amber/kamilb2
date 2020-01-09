<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Entity\Parser;
use App\Form\ParsePageType;
use App\Repository\ParserRepository;

use App\Service\ParserHelper;
use App\Service\TranslationHelper;
use App\Service\UploadHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/parser")
 */
class ParserController extends AbstractController
{
    /**
     * @Route("/", name="parser_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('backend/parser/index.html.twig', [

        ]);
    }

    /**
     * @Route("/page", name="parse_page", methods={"GET","POST"})
     */
    public function page(Request $request, ParserHelper $parserHelper, TranslationHelper $translationHelper, UploadHelper $uploadHelper): Response
    {
        $parser = new Parser();

        $form = $this->createForm(ParsePageType::class, $parser);
        $form->handleRequest($request);

        $hm = $uploadHelper->uploadExternalFile('https://i.pinimg.com/originals/b4/e0/29/b4e0291ae60c26b6809f91269b82c5ba.jpg');

        echo '<pre>';
        print_r($hm);
        echo '</pre>';

        if ($form->isSubmitted() && $form->isValid()) {
            $parseResult = $parserHelper->parsePage($form['pageUrl']->getData(), 'cosmo.ru');

            if (!empty($parseResult['title']) && !empty($parseResult['content'])) {
                /*$article = new Article();

                $article->setPub(false);
                $article->setPub(false);*/



                /*$titleTranslation = $translationHelper->translate($parseResult['title'], 'plain');
                $previewContentTranslation = $translationHelper->translate($parseResult['preview_content']);
                $contentTranslation = $translationHelper->translate($parseResult['content']);

                echo $parseResult['image_src'] . '<br/>';

                echo '$titleTranslation<pre>';
                print_r($titleTranslation);
                echo '</pre>';

                echo '$previewContentTranslation<pre>';
                print_r($previewContentTranslation);
                echo '</pre>';

                echo '$contentTranslation<pre>';
                print_r($contentTranslation);
                echo '</pre>';*/
            }
        }

        return $this->render('backend/parser/parse_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
