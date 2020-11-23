<?php


namespace App\Service;


use App\Entity\Pointofsale;
use App\Entity\Search;
use App\Repository\SaleRepository;
use App\Repository\SimCardRepository;
use App\Repository\TradeRepository;

class agentStat
{


    private $saleRepository;
    private $tradeRepository;
    private $simCardRepository;

    public function __construct( SaleRepository $saleRepository, TradeRepository $tradeRepository, SimCardRepository $simCardRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->tradeRepository = $tradeRepository;
        $this->simCardRepository = $simCardRepository;
    }

    private function getStartDate(Search $search)
    {
        $startDate = new \DateTime('-1 day');

        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        return$startDate;
    }

    private function getEndDate(Search $search)
    {
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $endDate;
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualToBankByPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualToBankByPointofsaleTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualFromBankToPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromBankToPointofsaleTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualToPosByPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();

        return $this->tradeRepository->findVirtualToOtherByPosTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualFromPosToPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromPosToOtherTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualToMasterByPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualToMasterByPosTotal($startDate, $endDate, $id, $master);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Pointofsale $pointofsale
     * @return mixed
     */
    public function getVirtualFromMasterToPointofsaleTotal(Search $search, Pointofsale $pointofsale)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $pointofsale->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualFromMasterToPosTotal($startDate, $endDate, $id, $master);
    }
}