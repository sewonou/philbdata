<?php

namespace App\Entity;

use App\Repository\PriceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceCategoryRepository::class)
 */
class PriceCategory
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
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=PriceList::class, mappedBy="priceCategory")
     */
    private $priceLists;

    public function __construct()
    {
        $this->priceLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|PriceList[]
     */
    public function getPriceLists(): Collection
    {
        return $this->priceLists;
    }

    public function addPriceList(PriceList $priceList): self
    {
        if (!$this->priceLists->contains($priceList)) {
            $this->priceLists[] = $priceList;
            $priceList->setPriceCategory($this);
        }

        return $this;
    }

    public function removePriceList(PriceList $priceList): self
    {
        if ($this->priceLists->contains($priceList)) {
            $this->priceLists->removeElement($priceList);
            // set the owning side to null (unless already changed)
            if ($priceList->getPriceCategory() === $this) {
                $priceList->setPriceCategory(null);
            }
        }

        return $this;
    }
}
