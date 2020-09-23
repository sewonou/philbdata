<?php


namespace App\Service;


use App\Entity\Search;
use App\Entity\Trader;
use App\Repository\ControlRepository;
use App\Repository\SaleRepository;
use App\Repository\TraderRepository;

class TraderStat
{
    private $pointOfSaleRepository;
    private $saleRepository;
    private $balanceRepository;
    private $tradeRepository;
    private $traderRepository;
    private $controlRepository;

    public function __construct(TraderRepository $traderRepository, ControlRepository $controlRepository, SaleRepository $saleRepository)
    {
        $this->traderRepository = $traderRepository;
        $this->controlRepository = $controlRepository;
        $this->saleRepository = $saleRepository;
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
        $startDate = new \DateTime('-1 day');
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        $length = $startDate->diff($endDate);
        $length = $length->format('%d');
        $length = ($length == 0)? ($length + 1) : $length;
        $dailyGoal = 500;
        //dump($length);
        //die();
        return $totalPointofsale * $length * $dailyGoal ;
    }

    public function getTraderInput(?Trader $trader, ?Search $search)
    {

        $startDate = new \DateTime('-1 day');
        $endDate = new \DateTime('-1 day') ;
        if(null != $search->getStartAt()){
            $startDate = $search->getStartAt();
        }
        if(null != $search->getEndAt()){
            $endDate = $search->getEndAt();
        }
        return $this->saleRepository->findSaleByTrader(true, $trader, $startDate, $endDate);
    }
}