<?php

namespace App\Entity;

use App\Repository\PriceListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceListRepository::class)
 */
class PriceList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $minRange;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $maxRange;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $minPrice;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $maxPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $commission;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $posCommission;

    /**
     * @ORM\Column(type="float")
     */
    private $dealerCommission;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="priceLists")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=PriceCategory::class, inversedBy="priceLists")
     */
    private $priceCategory;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinRange(): ?int
    {
        return $this->minRange;
    }

    public function setMinRange(?int $minRange): self
    {
        $this->minRange = $minRange;

        return $this;
    }

    public function getMaxRange(): ?int
    {
        return $this->maxRange;
    }

    public function setMaxRange(?int $maxRange): self
    {
        $this->maxRange = $maxRange;

        return $this;
    }

    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    public function setMinPrice(?int $minPrice): self
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(?float $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getPosCommission(): ?float
    {
        return $this->posCommission;
    }

    public function setPosCommission(?float $posCommission): self
    {
        $this->posCommission = $posCommission;

        return $this;
    }

    public function getDealerCommission(): ?float
    {
        return $this->dealerCommission;
    }

    public function setDealerCommission(float $dealerCommission): self
    {
        $this->dealerCommission = $dealerCommission;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPriceCategory(): ?PriceCategory
    {
        return $this->priceCategory;
    }

    public function setPriceCategory(?PriceCategory $priceCategory): self
    {
        $this->priceCategory = $priceCategory;

        return $this;
    }


}
