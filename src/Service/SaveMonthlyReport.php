<?php


namespace App\Service;


class SaveMonthlyReport
{
    private $save;
    public function __construct(Save $save)
    {

        $this->save = $save;
    }

    public function getLine(Reader $reader)
    {
        $msisdnTitle = $reader->getTitle('NUMERO DES PDV');
        $posNameTitle = $reader->getTitle('NOMS DES PDV');
        $profileTitle = $reader->getTitle('PROFIL');
        $depositCountTitle = $reader->getTitle('VOLUME DES DEPOTS');
        $depositValueTitle = $reader->getTitle('VALEUR DES DEPOTS');
        $withdrawalCountTitle= $reader->getTitle('VOLUME DES RETRAITS');
        $withdrawalValueTitle =$reader->getTitle('VALEUR DES RETRAITS');
        $posCommissionTitle = $reader->getTitle('COMMISION DES PDV');
        $dealerCommissionTitle = $reader->getTitle('COMMISSION DU DEALER');

        $title = compact('msisdnTitle', 'posNameTitle', 'profileTitle', 'dealerCommissionTitle', 'posCommissionTitle', 'withdrawalValueTitle', 'withdrawalCountTitle', 'depositValueTitle', 'depositCountTitle');

        return $title;
    }

    public function getValue($value, $title)
    {
        $msisdn = $value[$title['msisdnTitle']];
        $posName = $value[$title['posNameTitle']];
        $profile = $value[$title['profileTitle']];
        $depositCount = (int)$value[$title['depositCountTitle']];
        $depositValue = (float)$value[$title['depositValueTitle']];
        $withdrawalCount = (float)$value[$title['withdrawalCountTitle']];
        $withdrawalValue = (float)$value[$title['withdrawalValueTitle']];
        $dealerCommission = (float)$value[$title['dealerCommissionTitle']];
        $posCommission = (float)$value[$title['posCommissionTitle']];



        return compact('msisdn', 'posName', 'profile', 'depositCount', 'depositValue', 'withdrawalCount', 'withdrawalValue', 'dealerCommission', 'posCommission');
    }

    public function save($value)
    {

        $this->save->addMonthlyReport($value);
    }
}