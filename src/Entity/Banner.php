<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BannerRepository")
 * @ORM\Table(
 *     indexes = {
 *          @ORM\Index(name = "pub_index", columns = {"pub"}),
 *          @ORM\Index(name = "is_index_page_index", columns = {"on_index"}),
 *          @ORM\Index(name = "is_article_page_index", columns = {"on_article"}),
 *          @ORM\Index(name = "is_article_category_page_index", columns = {"on_article_category"}),
 *     }
 * )
 */
class Banner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageHash;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onIndex;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onArticle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onArticleCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageHash(): ?string
    {
        return $this->imageHash;
    }

    public function setImageHash(string $imageHash): self
    {
        $this->imageHash = $imageHash;

        return $this;
    }

    public function getOnIndex(): ?bool
    {
        return $this->onIndex;
    }

    public function setOnIndex(bool $onIndex): self
    {
        $this->onIndex = $onIndex;

        return $this;
    }

    public function getOnArticle(): ?bool
    {
        return $this->onArticle;
    }

    public function setOnArticle(bool $onArticle): self
    {
        $this->onArticle = $onArticle;

        return $this;
    }

    public function getOnArticleCategory(): ?bool
    {
        return $this->onArticleCategory;
    }

    public function setOnArticleCategory(bool $onArticleCategory): self
    {
        $this->onArticleCategory = $onArticleCategory;

        return $this;
    }
}
