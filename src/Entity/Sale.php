<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 * @UniqueEntity(
 *     fields={"refId"},
 *     message="Cet identifiant est unique"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Sale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SimCard::class)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez sélectionner un numéro valid")
     */
    private $msisdn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dComm = 0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $posComm = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transactionAt;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class)
     */
    private $type;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Regex(
     *     pattern="^[0-9]+$",
     *     htmlPattern="^[0-9]+$",
     *     message="Le numéro doit être composé de chiffre uniquement"
     * )
     */
    private $refId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDComm(): ?float
    {
        return $this->dComm;
    }

    public function setDealerCommission(?float $dComm): self
    {
        $this->dComm = $dComm;

        return $this;
    }

    public function getPosComm(): ?float
    {
        return $this->posComm;
    }

    public function setPosComm(?float $posComm): self
    {
        $this->posComm = $posComm;

        return $this;
    }

    public function getTransactionAt(): ?\DateTimeInterface
    {
        return $this->transactionAt;
    }

    public function setTransactionAt(?\DateTimeInterface $transactionAt): self
    {
        $this->transactionAt = $transactionAt;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function setRefId(?string $refId): self
    {
        $this->refId = $refId;

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
}
