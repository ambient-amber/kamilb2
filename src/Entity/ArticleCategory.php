<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleCategoryRepository")
 * @ORM\Table(
 *     indexes = {
 *          @ORM\Index(name = "pub_index", columns = {"pub"}),
 *          @ORM\Index(name = "url_index", columns = {"url"})
 *     }
 * )
 */
class ArticleCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleCategoryTranslation", mappedBy="category", orphanRemoval=true, cascade={"persist"})
     */
    private $articleCategoryTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category", orphanRemoval=true)
     */
    private $articles;

    /**
     * Отобранный перевод согласно текущей локали
     */
    private $relevantTranslation;

    public function __construct()
    {
        $this->articleCategoryTranslations = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPub(): ?bool
    {
        return $this->pub;
    }

    public function setPub(bool $pub): self
    {
        $this->pub = $pub;

        return $this;
    }

    /**
     * @return Collection|ArticleCategoryTranslation[]
     */
    public function getArticleCategoryTranslations(): Collection
    {
        return $this->articleCategoryTranslations;
    }

    public function addArticleCategoryTranslation(ArticleCategoryTranslation $articleCategoryTranslation): self
    {
        if (!$this->articleCategoryTranslations->contains($articleCategoryTranslation)) {
            $this->articleCategoryTranslations[] = $articleCategoryTranslation;
            $articleCategoryTranslation->setCategory($this);
        }

        return $this;
    }

    public function removeArticleCategoryTranslation(ArticleCategoryTranslation $articleCategoryTranslation): self
    {
        if ($this->articleCategoryTranslations->contains($articleCategoryTranslation)) {
            $this->articleCategoryTranslations->removeElement($articleCategoryTranslation);
            // set the owning side to null (unless already changed)
            if ($articleCategoryTranslation->getCategory() === $this) {
                $articleCategoryTranslation->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    public function getRelevantTranslation(): ?ArticleCategoryTranslation
    {
        return $this->relevantTranslation;
    }

    public function setRelevantTranslation(ArticleCategoryTranslation $translation): self
    {
        $this->relevantTranslation = $translation;

        return $this;
    }
}
