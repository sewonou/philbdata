<?php

namespace App\Entity;

use App\Repository\PointofsaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PointofsaleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Pointofsale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=SimCard::class, inversedBy="pointofsale", cascade={"persist", "remove"})
     * @Assert\NotBlank(message="Veuillez chosir un numéro valide.")
     * @Assert\Valid()
     */
    private $msisdn;


    /**
     * @ORM\ManyToOne(targetEntity=District::class)
     * @Assert\NotBlank(message="Veuillez choisir un quartier valide")
     * @Assert\Valid()
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="La localisation ne doit pas être vide")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     *
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=85, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\OneToMany(targetEntity=Control::class, mappedBy="pointofsale", cascade={"persist", "remove"})
     */
    private $controls;

    public function __construct()
    {
        $this->controls = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initialized()
    {
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMsisdn(): ?SimCard
    {
        return $this->msisdn;
    }

    public function setMsisdn(?SimCard $msisdn): self
    {
        $this->msisdn = $msisdn;

        return $this;
    }


    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact($contact): self
    {
        $this->contact = $contact;

        return $this;
    }


    /**
     * @return Collection|Control[]
     */
    public function getControls(): Collection
    {
        return $this->controls;
    }

    public function addControl(Control $control): self
    {
        if (!$this->controls->contains($control)) {
            $this->controls[] = $control;
            $control->setPointofsale($this);
        }

        return $this;
    }

    public function removeControl(Control $control): self
    {
        if ($this->controls->contains($control)) {
            $this->controls->removeElement($control);
            // set the owning side to null (unless already changed)
            if ($control->getPointofsale() === $this) {
                $control->setPointofsale(null);
            }
        }

        return $this;
    }

    public function getName()
    {
        return $this->getMsisdn()->getName();
    }
}
