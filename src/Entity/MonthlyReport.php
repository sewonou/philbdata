<?php

namespace App\Entity;

use App\Repository\MonthlyReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MonthlyReportRepository::class)
 */
class MonthlyReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=SimCard::class, inversedBy="monthlyReports")
     */
    private $msisdn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $depositCount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $depositValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $withdrawalCount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $withdrawalValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $posCommission;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dealerCommission;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function initialized()
    {
        $this->createdAt = new \DateTime();
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

    public function getDepositCount(): ?int
    {
        return $this->depositCount;
    }

    public function setDepositCount(?int $depositCount): self
    {
        $this->depositCount = $depositCount;

        return $this;
    }

    public function getDepositValue(): ?float
    {
        return $this->depositValue;
    }

    public function setDepositValue(?float $depositValue): self
    {
        $this->depositValue = $depositValue;

        return $this;
    }

    public function getWithdrawalCount(): ?int
    {
        return $this->withdrawalCount;
    }

    public function setWithdrawalCount(?int $withdrawalCount): self
    {
        $this->withdrawalCount = $withdrawalCount;

        return $this;
    }

    public function getWithdrawalValue(): ?float
    {
        return $this->withdrawalValue;
    }

    public function setWithdrawalValue(?float $withdrawalValue): self
    {
        $this->withdrawalValue = $withdrawalValue;

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

    public function setDealerCommission(?float $dealerCommission): self
    {
        $this->dealerCommission = $dealerCommission;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
