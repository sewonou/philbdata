<?php

namespace App\Entity;

use App\Repository\SimCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SimCardRepository::class)
 * @UniqueEntity(
 *      fields={"msisdn"},
 *      message = "Ce numéro existe déja!",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class SimCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Veuillez sasir un numéro valide")
     * @Assert\Regex(
     *     pattern="^[0-9]+$",
     *     htmlPattern="^[0-9]+$",
     *     message="Le numéro doit être composé de chiffre uniquement"
     * )
     */
    private $msisdn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class)
     * @Assert\NotBlank(message="Veuillez sélectionner un profil valide")
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity=MasterSim::class)
     * @Assert\NotBlank(message="Veuillez sélectionner un numéro master valide")
     */
    private $master;

    /**
     * @ORM\OneToOne(targetEntity=Trader::class, mappedBy="msisdn", cascade={"persist", "remove"})
     */
    private $trader;

    /**
     * @ORM\OneToOne(targetEntity=Pointofsale::class, mappedBy="msisdn", cascade={"persist", "remove"})
     */
    private $pointofsale;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="msisdn")
     */
    private $sales;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function __construct()
    {
        $this->sales = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     */
    public function initialized()
    {
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getMsisdn(): ?string
    {
        return $this->msisdn;
    }

    public function setMsisdn(string $msisdn): self
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }


    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getMaster(): ?MasterSim
    {
        return $this->master;
    }

    public function setMaster(?MasterSim $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getTrader(): ?Trader
    {
        return $this->trader;
    }

    public function setTrader(?Trader $trader): self
    {
        $this->trader = $trader;

        // set (or unset) the owning side of the relation if necessary
        $newMsisdn = null === $trader ? null : $this;
        if ($trader->getMsisdn() !== $newMsisdn) {
            $trader->setMsisdn($newMsisdn);
        }

        return $this;
    }


    public function getPointofsale(): ?Pointofsale
    {
        return $this->pointofsale;
    }

    public function setPointofsale(?Pointofsale $pointofsale): self
    {
        $this->pointofsale = $pointofsale;

        // set (or unset) the owning side of the relation if necessary
        $newMsisdn = null === $pointofsale ? null : $this;
        if ($pointofsale->getMsisdn() !== $newMsisdn) {
            $pointofsale->setMsisdn($newMsisdn);
        }

        return $this;
    }

    public function getFullName()
    {
        $fulName = $this->msisdn .' '. $this->getProfile()->getTitle();
        if($this->getPointofsale()){
            $fulName =  $this->msisdn .' '. $this->getPointofsale()->getName() .' '. $this->getProfile()->getTitle();
        }elseif ($this->getTrader()){
            $fulName =  $this->msisdn .' '. $this->getTrader()->getName() .' '. $this->getProfile()->getTitle();
        }
        return $fulName;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getSale(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setMsisdn($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->contains($sale)) {
            $this->sales->removeElement($sale);
            // set the owning side to null (unless already changed)
            if ($sale->getMsisdn() === $this) {
                $sale->setMsisdn(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
