<?php


namespace App\Service;


class SaveOneTransaction
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
        $transactionDateTitle = $reader->getTitle('TRANSACTION DATE');
        $typeTitle= $reader->getTitle('TYPE');
        $toSimTitle =$reader->getTitle('DESTINATION');
        $fromSimTitle = $reader->getTitle('SOURCE');

        $title = compact('idTitle', 'amountTitle', 'transactionDateTitle', 'typeTitle', 'fromSimTitle', 'toSimTitle');

        return $title;
    }

    public function getValue($value, $title)
    {
        $transactionAt = new \DateTime($value[$title['transactionDateTitle']]);
        $amount = (float)$value[$title['amountTitle']];
        $fromSim = $value[$title['fromSimTitle']];
        $toSim = $value[$title['toSimTitle']];
        $type = $value[$title['typeTitle']];



        return compact('id', 'transactionAt', 'amount', 'fromSim', 'toSim', 'type');
    }

    public function save($value)
    {
        $this->save->addOnePosTransaction($value);
    }
}