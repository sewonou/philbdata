<?php


namespace App\Service;


use App\Entity\Search;
use App\Entity\Trader;
use App\Repository\ControlRepository;
use App\Repository\SaleRepository;
use App\Repository\SimCardRepository;
use App\Repository\TradeRepository;
use App\Repository\TraderRepository;

class TraderStat
{
    private $pointOfSaleRepository;
    private $saleRepository;
    private $balanceRepository;
    private $tradeRepository;
    private $traderRepository;
    private $controlRepository;
    private $simCardRepository;

    public function __construct(TraderRepository $traderRepository, ControlRepository $controlRepository, SaleRepository $saleRepository, TradeRepository $tradeRepository, SimCardRepository $simCardRepository)
    {
        $this->traderRepository = $traderRepository;
        $this->controlRepository = $controlRepository;
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

    public function getTraderPointofsale(?Trader $trader):int
    {
        return  count($this->controlRepository->findTraderPointofsale($trader, true));
    }

    public function getTraderPointofsaleByProfile(?Trader $trader, ?string $profile):int
    {
        return  count($this->controlRepository->findTraderPointofsaleByProfile($trader, true, $profile));
    }

    public function getTraderMonthlyGoal(?Trader $trader)
    {
        $totalPointofsale = $this->getTraderPointofsale($trader);
        $commercialMonth = 30 ;
        $dailyGoal = 500;

        return $totalPointofsale * $commercialMonth * $dailyGoal ;

    }

    public function getTraderPeriodGoal(?Trader $trader, ?Search $search)
    {
        $totalPointofsale = $this->getTraderPointofsale($trader);
        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate());
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        $length = $startDate->diff($endDate);
        $length = $length->format('%a');
        $length = ($length == 0)? ($length + 1) : $length;
        $dailyGoal = 500;
        //dump($length);
        //die();
        return $totalPointofsale * $length * $dailyGoal ;
    }

    public function getTraderInput(?Trader $trader, ?Search $search)
    {

        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate());
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByTrader(true, $trader, $startDate, $endDate);
    }

    public function getTraderInputByDay(?Trader $trader, ?Search $search)
    {

        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate());
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByTraderByDay(true, $trader, $startDate, $endDate);
    }

    public function getSaleForTrader(?Trader $trader, ?Search $search)
    {

        $startDate = new \DateTime($this->saleRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate());
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleForTrader(true, $trader, $startDate, $endDate);
    }

    public function getTraderInputByDayWithLimit(?Trader $trader, int $limit)
    {

        return $this->saleRepository->findSaleByTraderByDayWithLimit(true, $trader, $limit);
    }


    public function getGiveSendByTrader(Search $search, int $id)
    {
        $startDate = new \DateTime($this->tradeRepository->findLastDate());
        $endDate = new \DateTime($this->tradeRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }

        return $this->tradeRepository->findGiveSendByTrader($startDate, $endDate, $id);
    }



    public function getGiveReceivedByTrader(Search $search, int $id)
    {
        $startDate = new \DateTime($this->tradeRepository->findLastDate());
        $endDate = new \DateTime($this->saleRepository->findLastDate()) ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }

        return $this->tradeRepository->findGiveReceivedByTrader($startDate, $endDate, $id);
    }

    public function getLastWeekCommission($results)
    {
        $commissions = [];
        foreach ($results as  $value ){
            $commissions[] = round($value['dComm']/$value['total'], 1);
        }
        $inverse = array_reverse($commissions);
        $inverse = '['. implode(',', $inverse) .']';
        return $inverse;
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualToBankByTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualToBankByTrader($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualFromBankToTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromBankToTrader($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualToPosByTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualToPosByTrader($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualFromPosToTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromPosToTrader($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualToMasterByTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualToMasterByTrader($startDate, $endDate, $id, $master);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualFromMasterToTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualFromMasterToTrader($startDate, $endDate, $id, $master);
    }


    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getVirtualToBankByTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualToBankByTraderTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getVirtualFromBankToTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromBankToTraderTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getVirtualToPosByTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualToPosByTraderTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualFromPosToTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findVirtualFromPosToTraderTotal($startDate, $endDate, $id);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualToMasterByTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualToMasterByTraderTotal($startDate, $endDate, $id, $master);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getVirtualFromMasterToTraderTotal(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        $master = $this->simCardRepository->findOneBy(['msisdn'=>'22897391919']);
        return $this->tradeRepository->findVirtualFromMasterToTraderTotal($startDate, $endDate, $id, $master);
    }

    public function getOpenGiveInByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findOpenGiveInByTraders($startDate, $endDate);
    }

    public function getOpenGiveOutByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findOpenGiveOutByTraders($startDate, $endDate);
    }

    public function getOpenGiveInByTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findOpenGiveByTrader($startDate, $endDate, $id);
    }
}