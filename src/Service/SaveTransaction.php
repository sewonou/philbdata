<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SaveTransaction
{

    private $save;
    public function __construct(Save $save)
    {

        $this->save = $save;
    }

    public function getLine(Reader $reader)
    {
        $idTitle = $reader->getTitle('REFERENCEID');
        $amountTitle = $reader->getTitle('AMOUNT');
        $dealerCommissionTitle = $reader->getTitle('DEALER_COMMISSION');
        $posCommissionTitle = $reader->getTitle('POS_COMMISSION');
        $transactionDateTitle = $reader->getTitle('TIMESTAMP');
        $typeTitle= $reader->getTitle('TYPE');
        $toSimTitle =$reader->getTitle('TOMSISDN');
        $fromSimTitle = $reader->getTitle('FRMSISDN');
        $toSimProfile = $reader->getTitle('TOPROFILE');
        $fromSimProfile = $reader->getTitle('FRPROFILE');
        $toSimName = $reader->getTitle('TO_POS_NAME');
        $fromSimName = $reader->getTitle('FR_POS_NAME');

        $title = compact('idTitle', 'amountTitle', 'dealerCommissionTitle', 'posCommissionTitle', 'transactionDateTitle', 'typeTitle', 'toSimTitle', 'fromSimTitle', 'toSimProfile', 'toSimName', 'fromSimProfile', 'fromSimName');

        return $title;
    }

    public function getValue($value, $title)
    {
        $id = $value[$title['idTitle']];
        $transactionAt = new \DateTime($value[$title['transactionDateTitle']]);
        $amount = (float)$value[$title['amountTitle']];
        $dealerCommission = (float)$value[$title['dealerCommissionTitle']];
        $posCommission = (float)$value[$title['posCommissionTitle']];
        $fromSim = $value[$title['fromSimTitle']];
        $toSim = $value[$title['toSimTitle']];
        $type = $value[$title['typeTitle']];
        $fromSimName = $value[$title['fromSimName']];
        $fromSimProfile = $value[$title['fromSimProfile']];
        $toSimName = $value[$title['toSimName']];
        $toSimProfile = $value[$title['toSimProfile']];


        return compact('id', 'transactionAt', 'amount', 'dealerCommission', 'posCommission', 'fromSim', 'toSim', 'type', 'fromSimName', 'fromSimProfile', 'toSimName', 'toSimProfile');
    }

    public function save($value)
    {
        $this->save->addTransaction($value);
    }

    public function saveDeposit($value)
    {
        $this->save->addDeposit($value);
    }

    public function saveWithdrawal($value)
    {
        $this->save->addWithdrawal($value);
    }

    public function saveOtherTransaction($value)
    {
        $this->save->addOtherTransaction($value);
    }
}