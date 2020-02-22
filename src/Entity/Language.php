<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $textId;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $articleTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PageTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $pageTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleCategoryTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $articleCategoryTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MetaData", mappedBy="language", orphanRemoval=true)
     */
    private $metaData;

    public function __construct()
    {
        $this->articleTranslations = new ArrayCollection();
        $this->pageTranslations = new ArrayCollection();
        $this->articleCategoryTranslations = new ArrayCollection();
        $this->metaData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextId(): ?string
    {
        return $this->textId;
    }

    public function setTextId(string $textId): self
    {
        $this->textId = $textId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|ArticleTranslation[]
     */
    public function getArticleTranslations(): Collection
    {
        return $this->articleTranslations;
    }

    public function addArticleTranslation(ArticleTranslation $articleTranslation): self
    {
        if (!$this->articleTranslations->contains($articleTranslation)) {
            $this->articleTranslations[] = $articleTranslation;
            $articleTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeArticleTranslation(ArticleTranslation $articleTranslation): self
    {
        if ($this->articleTranslations->contains($articleTranslation)) {
            $this->articleTranslations->removeElement($articleTranslation);
            // set the owning side to null (unless already changed)
            if ($articleTranslation->getLanguage() === $this) {
                $articleTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageTranslation[]
     */
    public function getPageTranslations(): Collection
    {
        return $this->pageTranslations;
    }

    public function addPageTranslation(PageTranslation $pageTranslation): self
    {
        if (!$this->pageTranslations->contains($pageTranslation)) {
            $this->pageTranslations[] = $pageTranslation;
            $pageTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removePageTranslation(PageTranslation $pageTranslation): self
    {
        if ($this->pageTranslations->contains($pageTranslation)) {
            $this->pageTranslations->removeElement($pageTranslation);
            // set the owning side to null (unless already changed)
            if ($pageTranslation->getLanguage() === $this) {
                $pageTranslation->setLanguage(null);
            }
        }

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
            $articleCategoryTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeArticleCategoryTranslation(ArticleCategoryTranslation $articleCategoryTranslation): self
    {
        if ($this->articleCategoryTranslations->contains($articleCategoryTranslation)) {
            $this->articleCategoryTranslations->removeElement($articleCategoryTranslation);
            // set the owning side to null (unless already changed)
            if ($articleCategoryTranslation->getLanguage() === $this) {
                $articleCategoryTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MetaData[]
     */
    public function getMetaData(): Collection
    {
        return $this->metaData;
    }

    public function addMetaData(MetaData $metaData): self
    {
        if (!$this->metaData->contains($metaData)) {
            $this->metaData[] = $metaData;
            $metaData->setLanguage($this);
        }

        return $this;
    }

    public function removeMetaData(MetaData $metaData): self
    {
        if ($this->metaData->contains($metaData)) {
            $this->metaData->removeElement($metaData);
            // set the owning side to null (unless already changed)
            if ($metaData->getLanguage() === $this) {
                $metaData->setLanguage(null);
            }
        }

        return $this;
    }
}
