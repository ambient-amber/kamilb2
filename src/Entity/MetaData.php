<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetaDataRepository")
 * @ORM\Table(
 *     indexes = {
 *         @ORM\Index(name = "pub_index", columns = {"pub"}),
 *         @ORM\Index(name = "sort_index", columns = {"sort"}),
 *         @ORM\Index(name = "url_language_uniq", columns = {"url", "language_id"}),
 *     }
 * )
 * @UniqueEntity(
 *     fields={"url", "language"},
 *     message="Meta data for this url allready exists"
 * )
 */
class MetaData
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
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRegexp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keyWords;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language", inversedBy="metaData")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $sort;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isTemplate;

    public function __construct()
    {
        $this->sort = 0;
        $this->isTemplate = 0;
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

    public function getIsRegexp(): ?bool
    {
        return $this->isRegexp;
    }

    public function setIsRegexp(bool $isRegexp): self
    {
        $this->isRegexp = $isRegexp;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getKeyWords(): ?string
    {
        return $this->keyWords;
    }

    public function setKeyWords(?string $keyWords): self
    {
        $this->keyWords = $keyWords;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getIsTemplate(): ?bool
    {
        return $this->isTemplate;
    }

    public function setIsTemplate(bool $isTemplate): self
    {
        $this->isTemplate = $isTemplate;

        return $this;
    }
}
