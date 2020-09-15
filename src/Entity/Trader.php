<?php

namespace App\Entity;

use App\Repository\TraderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TraderRepository::class)
 * @Vich\Uploadable()
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"fullName"},
 *     message="Cet nom est déjà en registrer",
 *     )
 */
class Trader
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=SimCard::class, inversedBy="trader", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez choisir un numéro valide")
     */
    private $msisdn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom valide")
     */
    private $fullName;

    /**
     * @Assert\File(
     *     maxSize="1024k",
     *     maxSizeMessage="Le fichier ne doit pas excéder 1024Ko"
     * )
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="pictureName")
     * @var File|null
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTrader;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="traders")
     * @Assert\Valid()
     */
    private $region;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $msisdnName;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMsisdn(): ?SimCard
    {
        return $this->msisdn;
    }

    public function setMsisdn(?SimCard $msisdn): self
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $pictureFile
     * @throws \Exception
     */
    public function setPictureFile(?File  $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;
        if (null !== $pictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updateAt = new \DateTimeImmutable();
        }

    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }


    public function getIsTrader(): ?bool
    {
        return $this->isTrader;
    }

    public function setIsTrader(?bool $isTrader): self
    {
        $this->isTrader = $isTrader;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMsisdnName(): ?string
    {
        return $this->msisdnName;
    }

    public function setMsisdnName(?string $msisdnName): self
    {
        $this->msisdnName = $msisdnName;

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
