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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $textId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $articleTranslations;

    public function __construct()
    {
        $this->articleTranslations = new ArrayCollection();
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
}
