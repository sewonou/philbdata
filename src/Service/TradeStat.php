<?php


namespace App\Service;


use App\Entity\Search;
use App\Entity\Trader;
use App\Repository\MasterSimRepository;
use App\Repository\SimCardRepository;
use App\Repository\TradeRepository;

class TradeStat
{
    private $tradeRepository;
    private $simCardRepository;

    public function __construct(TradeRepository $tradeRepository, SimCardRepository $simCardRepository)
    {
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
     *
     * Achat de virtuel en banque par les commerciaux totalm d'une période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBankByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByTraders($startDate, $endDate);
    }

    /**
     * Achat de virtuel en banque par les commerciaux par jour suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBankByTradersByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByTradersByDay($startDate, $endDate);
    }

    /**
     * Retrait des espèces en banque par les Commerciaux total suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBankByTraders(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByTraders($startDate, $endDate);
    }

    /**
     * Retrait des espèces en banque par les Commerciaux par jour suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBankByTradersByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByTradersByDay($startDate, $endDate);
    }

    /**
     * Achat de virtuel en ban que par les points de vente total suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBankByPointofsales(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByPointofsales($startDate, $endDate);
    }

    /**
     * Achat de virtuel en banque par les points de vente par jour suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBankByPointofsalesByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByPointofsalesByDay($startDate, $endDate);
    }

    /**
     * Retrait des espèces en banque par les Points de Vente total suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBankByPointofsales(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByPointofsales($startDate, $endDate);
    }

    /**
     * Retrait des èspèces en banque par les Point de ventes par jour suivant la priode
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBankByPointofsalesByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByPointofsalesByDay($startDate, $endDate);
    }

    /**
     * Achat de give en bank par le réseau (Commerciaux et PDV) total suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBank(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBank($startDate, $endDate);
    }

    /**
     * Achat de give en bank par le réseau (Commerciaux et PDV) par jour suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveInBankByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveInBankByDay($startDate, $endDate);
    }

    /**
     * Retrait d'espèces en banque par le réseau (Commerciaux et PDV) total suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBank(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBank($startDate, $endDate);
    }

    /**
     * Retrait d'espèces en banque par le réseau (Commerciaux et PDV) par jour suivant la période
     * @param Search $search
     * @return mixed
     */
    public function getGiveOutBankByDay(Search $search)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findGiveOutBankByDay($startDate, $endDate);
    }

    /**
     * Vente des give par rapport à un profile total suivant la période
     * @param Search $search
     * @param string $profile
     * @return mixed
     */
    public function getSaleByTraders(Search $search, string  $profile)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findSaleByTraders($startDate, $endDate, $profile);
    }

    /**
     * Vente des give par rapport à un profile total groupé par jour suivant la période
     * @param Search $search
     * @param string $profile
     * @return mixed
     */
    public function getSaleByTradersByDay(Search $search, string  $profile)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;

        return $this->tradeRepository->findSaleByTradersByDay($startDate, $endDate, $profile);
    }

    /**
     * Vente des gives par un commercial total
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getSaleByTrader(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        $master = $this->simCardRepository->findBy(['msisdn'=> '22897391919']);
        return $this->tradeRepository->findSaleByTrader($startDate, $endDate, $id, $master);
    }

    /**
     * Vente des gives par un Commercial groupé par jour
     * @param Search $search
     * @param Trader $trader
     * @return mixed
     */
    public function getSaleByTraderByDay(Search $search, Trader $trader)
    {
        $startDate = $this->getStartDate($search);
        $endDate = $this->getEndDate($search) ;
        $id = $trader->getMsisdn()->getId();
        return $this->tradeRepository->findSaleByTradersByDay($startDate, $endDate, $id);
    }


}