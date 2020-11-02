<?php


namespace App\Service;


use App\Entity\Search;
use App\Repository\TradeRepository;

class TradeStat
{
    private $tradeRepository;

    public function __construct(TradeRepository $tradeRepository)
    {
        $this->tradeRepository = $tradeRepository;
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

    public function getGiveInBankByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByTraders($startDate, $endDate);
    }

    public function getGiveInBankByTradersByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByTradersByDay($startDate, $endDate);
    }

    public function getGiveOutBankByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByTraders($startDate, $endDate);
    }

    public function getGiveOutBankByTradersByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByTradersByDay($startDate, $endDate);
    }

    public function getGiveInBankByPointofsales(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByPointofsales($startDate, $endDate);
    }

    public function getGiveInBankByPointofsalesByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByPointofsalesByDay($startDate, $endDate);
    }

    public function getGiveOutBankByPointofsales(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByPointofsales($startDate, $endDate);
    }

    public function getGiveOutBankByPointofsalesByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByPointofsalesByDay($startDate, $endDate);
    }

    public function getGiveInBank(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBank($startDate, $endDate);
    }

    public function getGIveOutBank(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBank($startDate, $endDate);
    }
}