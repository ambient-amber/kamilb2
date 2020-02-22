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
 *          @ORM\Index(name = "is_mobile_index", columns = {"on_mobile"}),
 *          @ORM\Index(name = "is_tablet_index", columns = {"on_tablet"}),
 *          @ORM\Index(name = "is_desktop_index", columns = {"on_desktop"}),
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
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=191)
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

    /**
     * @ORM\Column(type="boolean")
     */
    private $onMobile;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onTablet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onDesktop;

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

    public function getOnMobile(): ?bool
    {
        return $this->onMobile;
    }

    public function setOnMobile(bool $onMobile): self
    {
        $this->onMobile = $onMobile;

        return $this;
    }

    public function getOnTablet(): ?bool
    {
        return $this->onTablet;
    }

    public function setOnTablet(bool $onTablet): self
    {
        $this->onTablet = $onTablet;

        return $this;
    }

    public function getOnDesktop(): ?bool
    {
        return $this->onDesktop;
    }

    public function setOnDesktop(bool $onDesktop): self
    {
        $this->onDesktop = $onDesktop;

        return $this;
    }
}
