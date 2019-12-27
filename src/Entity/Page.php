<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(
 *     indexes = {
 *         @ORM\Index(name = "pub_index", columns = {"pub"}),
 *         @ORM\Index(name = "show_in_footer_index", columns = {"show_in_footer"}),
 *         @ORM\Index(name = "url_index", columns = {"url"}),
 *     }
 * )
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showInFooter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PageTranslation", mappedBy="page", orphanRemoval=true)
     */
    private $pageTranslations;

    public function __construct()
    {
        $this->pageTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getShowInFooter(): ?string
    {
        return $this->showInFooter;
    }

    public function setShowInFooter(string $showInFooter): self
    {
        $this->showInFooter = $showInFooter;

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
            $pageTranslation->setPage($this);
        }

        return $this;
    }

    public function removePageTranslation(PageTranslation $pageTranslation): self
    {
        if ($this->pageTranslations->contains($pageTranslation)) {
            $this->pageTranslations->removeElement($pageTranslation);
            // set the owning side to null (unless already changed)
            if ($pageTranslation->getPage() === $this) {
                $pageTranslation->setPage(null);
            }
        }

        return $this;
    }
}
