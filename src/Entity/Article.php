<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(
 *     indexes = {
 *          @ORM\Index(name = "pub_index", columns = {"pub"}),
 *          @ORM\Index(name = "url_index", columns = {"url"}),
 *     }
 * )
 */
class Article
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
    private $imageName;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $imageHash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleTranslation", mappedBy="article", orphanRemoval=true, cascade={"persist"})
     */
    private $articleTranslations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pub;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleCategory", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateInsert;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $viewsCount;

    /**
     * Отобранный перевод согласно текущей локали
     */
    private $relevantTranslation;

    public function __construct()
    {
        $this->articleTranslations = new ArrayCollection();
        $this->dateInsert = new \DateTime();
        $this->viewsCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setImageHash(?string $imageHash): self
    {
        $this->imageHash = $imageHash;

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
            $articleTranslation->setArticle($this);
        }

        return $this;
    }

    public function removeArticleTranslation(ArticleTranslation $articleTranslation): self
    {
        if ($this->articleTranslations->contains($articleTranslation)) {
            $this->articleTranslations->removeElement($articleTranslation);
            // set the owning side to null (unless already changed)
            if ($articleTranslation->getArticle() === $this) {
                $articleTranslation->setArticle(null);
            }
        }

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCategory(): ?ArticleCategory
    {
        return $this->category;
    }

    public function setCategory(ArticleCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStatuses(): array
    {
        $statuses = [];

        $interval = date_diff(
            date_create(date('Y-m-d H:i:s')),
            date_create($this->getDateInsert()->format('Y-m-d H:i:s'))
        );

        if ($interval->format('%d') < 1) {
            $statuses['new'] = [
                'text_id' => 'new',
                'title_id' => 'new_status_title',
                'minutes_ago' => $interval->format('%i')
            ];
        }

        return $statuses;
    }

    public function getDateInsert(): ?\DateTimeInterface
    {
        return $this->dateInsert;
    }

    public function setDateInsert(\DateTimeInterface $dateInsert): self
    {
        $this->dateInsert = $dateInsert;

        return $this;
    }

    public function getViewsCount(): ?int
    {
        return $this->viewsCount;
    }

    public function setViewsCount(int $viewsCount): self
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    public function getRelevantTranslation(): ?ArticleTranslation
    {
        return $this->relevantTranslation;
    }

    public function setRelevantTranslation(ArticleTranslation $translation): self
    {
        $this->relevantTranslation = $translation;

        return $this;
    }
}
