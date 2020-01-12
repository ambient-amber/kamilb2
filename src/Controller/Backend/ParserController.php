<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Form\ParsePageType;
use App\Repository\ArticleRepository;
use App\Repository\LanguageRepository;
use App\Service\ParserHelper;
use App\Service\TranslationHelper;
use App\Utils\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function page(
        Request $request,
        ParserHelper $parserHelper,
        TranslationHelper $translationHelper,
        ArticleRepository $articleRepository,
        LanguageRepository $languageRepository,
        Slugger $slugger
    ): Response {
        $form = $this->createForm(ParsePageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sourceUrl = $form['pageUrl']->getData();

            // Проверка статьи на существование
            if (!$articleRepository->findBySource($sourceUrl)) {
                $parseResult = $parserHelper->parsePage($sourceUrl, 'cosmo.ru');

                // Минимальные требования для добавления статьи
                if (!empty($parseResult['title']) && !empty($parseResult['content'])) {
                    $article = new Article();

                    $article->setPub(false);
                    $article->setSource($sourceUrl);
                    $article->setImageHash($parseResult['image_hash']);
                    $article->setImageName($parseResult['image_name']);
                    $article->setUrl($slugger->slugify($parseResult['title']));

                    // Добавление перевода на русском
                    $translation = new ArticleTranslation();
                    $translation->setLanguage($languageRepository->findByTextId('ru'));
                    $translation->setTitle($parseResult['title']);
                    $translation->setPreviewContent($parseResult['preview_content']);
                    $translation->setContent($parseResult['content']);
                    $article->addArticleTranslation($translation);

                    // Добавление перевода на узбекском
                    $titleTranslation = $translationHelper->translate($parseResult['title'], 'plain');
                    $previewContentTranslation = $translationHelper->translate($parseResult['preview_content']);
                    $contentTranslation = $translationHelper->translate($parseResult['content']);

                    if ($titleTranslation && $contentTranslation) {
                        $translation = new ArticleTranslation();
                        $translation->setLanguage($languageRepository->findByTextId('uz'));
                        $translation->setTitle($titleTranslation[0]);
                        $translation->setContent($contentTranslation[0]);

                        if ($previewContentTranslation) {
                            $translation->setPreviewContent($previewContentTranslation[0]);
                        }

                        $article->addArticleTranslation($translation);
                    }

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($article);
                    $entityManager->flush();

                    if ($article->getId()) {
                        return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
                    }

                    // ToDo сделать добавление ошибок в форму
                }
            } else {
                echo 'Статья с таким источником уже существует';
            }
        }

        return $this->render('backend/parser/parse_page.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
