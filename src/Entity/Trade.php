<?php

namespace App\Entity;

use App\Repository\TradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TradeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Trade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity=SimCard::class)
     * @Assert\Valid()
     */
    private $fromMsisdn;

    /**
     * @ORM\ManyToOne(targetEntity=SimCard::class)
     * @Assert\Valid()
     */
    private $toMsisdn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transactionAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBankGive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOpenGive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankName;


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

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function setRefId(?string $refId): self
    {
        $this->refId = $refId;

        return $this;
    }

    public function getFromMsisdn(): ?SimCard
    {
        return $this->fromMsisdn;
    }

    public function setFromMsisdn(?SimCard $fromMsisdn): self
    {
        $this->fromMsisdn = $fromMsisdn;

        return $this;
    }

    public function getToMsisdn(): ?SimCard
    {
        return $this->toMsisdn;
    }

    public function setToMsisdn(?SimCard $toMsisdn): self
    {
        $this->toMsisdn = $toMsisdn;

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getIsBankGive(): ?bool
    {
        return $this->isBankGive;
    }

    public function setIsBankGive(?bool $isBankGive): self
    {
        $this->isBankGive = $isBankGive;

        return $this;
    }

    public function getIsOpenGive(): ?bool
    {
        return $this->isOpenGive;
    }

    public function setIsOpenGive(?bool $isOpenGive): self
    {
        $this->isOpenGive = $isOpenGive;

        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;

        return $this;
    }


}
