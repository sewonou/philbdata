<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SaveTransaction
{
    private $user;
    private $save;
    public function __construct(Security $security, Save $save)
    {
        $this->user = $security->getUser();
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

        $title = compact('idTitle', 'amountTitle', 'dealerCommissionTitle', 'posCommissionTitle', 'transactionDateTitle', 'typeTitle', 'toSimTitle', 'fromSimTitle');

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
        $user = $this->user;

        return compact('id', 'transactionAt', 'amount', 'dealerCommission', 'posCommission', 'fromSim', 'toSim', 'type', 'user');
    }

    public function save($value)
    {
        $this->save->addTransaction($value);
    }
}