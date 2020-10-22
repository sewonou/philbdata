<?php


namespace App\Service;


class CalcCommission
{
    /**
     * @param $value
     * @return float
     */
    public function getCommission($value)
    {
        $withdrawal = ['AGNT', 'AWITH', 'WITH', 'APPAGNT'];
        if(in_array($value['type'],$withdrawal)){
            $commission = $this->getAGNTCommission($value['amount']);
        }
        elseif($value['type'] == 'CSIN'){
            $commission = $this->getCSINCommission($value['amount']);
        }
        else{
            $commission = 0 ;
        }
        return $commission;
    }


    /**
     * @param $commission
     * @return float
     */
    public function getPosComm($commission)
    {

        return $commission*(80/100);
    }

    /**
     *
     * @param $commission
     * @return float
     */
    public function getDealerComm($commission)
    {

        return $commission*(20/100);
    }

    /**
     * @param $amount
     * @return float
     */
    public function getAGNTCommission($amount)
    {
        $commission = 0 ;
        if($amount<=5000){
            $commission = 45;
        }elseif($amount > 5000 and $amount <= 15000){
            $commission = 200;
        }elseif($amount > 15000 and $amount <= 20000){
            $commission = 200 ;
        }elseif($amount > 20000 and $amount <= 50000){
            $commission = 400 ;
        }elseif($amount > 50000 and $amount <= 100000){
            $commission = 900 ;
        }elseif($amount > 100000 and $amount <= 200000){
            $commission = 1500 ;
        }elseif($amount > 200000 and $amount <= 300000){
            $commission = 2200 ;
        }elseif($amount > 300000 and $amount <= 500000){
            $commission = 2475 ;
        }elseif($amount > 500000 and $amount <= 850000){
            $commission = 2585 ;
        }elseif($amount > 850000 and $amount <= 1000000){
            $commission = 2695 ;
        }elseif($amount > 1000000 and $amount <= 1500000){
            $commission = 3190 ;
        }elseif($amount > 1500000 and $amount <= 2000000){
            $commission = 4785 ;
        }
        return $commission;
    }

    /**
     * @param $amount
     * @return float
     */
    public function getCSINCommission($amount)
    {
        $commission = 0 ;
        if($amount<=5000){
            $commission = 25;
        }elseif($amount > 5000 and $amount <= 15000){
            $commission = 75;
        }elseif($amount > 15000 and $amount <= 20000){
            $commission = 150 ;
        }elseif($amount > 20000 and $amount <= 50000){
            $commission = 150 ;
        }elseif($amount > 50000 and $amount <= 100000){
            $commission = 300 ;
        }elseif($amount > 100000 and $amount <= 200000){
            $commission = 400 ;
        }elseif($amount > 200000 and $amount <= 300000){
            $commission = 400 ;
        }elseif($amount > 300000 and $amount <= 500000){
            $commission = 470 ;
        }elseif($amount > 500000 and $amount <= 850000){
            $commission = 500 ;
        }elseif($amount > 850000 and $amount <= 1000000){
            $commission = 750 ;
        }elseif($amount > 1000000 and $amount <= 1500000){
            $commission = 1000 ;
        }/*elseif($amount > 1500000 and $amount <= 2000000){
            $commission = 4785 ;
        }*/

        return $commission;
    }


}