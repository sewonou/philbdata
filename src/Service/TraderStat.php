<?php


namespace App\Service;


use App\Entity\Search;
use App\Entity\Trader;
use App\Repository\ControlRepository;
use App\Repository\SaleRepository;
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

    public function __construct(TraderRepository $traderRepository, ControlRepository $controlRepository, SaleRepository $saleRepository, TradeRepository $tradeRepository)
    {
        $this->traderRepository = $traderRepository;
        $this->controlRepository = $controlRepository;
        $this->saleRepository = $saleRepository;
        $this->tradeRepository = $tradeRepository;
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
}