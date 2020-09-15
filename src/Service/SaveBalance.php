<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class SaveBalance
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
        $executeAtTitle = $reader->getTitle('DATE_EXECUTION');
        $simTitle = $reader->getTitle('POS_MSISDN');
        $posNameTitle = $reader->getTitle('POS_NICKNAME');
        $profileTitle = $reader->getTitle('POS_ACCOUNTTYPE');
        $balanceTitle = $reader->getTitle('POS_SOLDE');

        $title = compact('executeAtTitle', 'simTitle', 'posNameTitle', 'profileTitle', 'balanceTitle');
        return $title;
    }

    public function getValue($value, $title)
    {
        $profile = $value[$title['profileTitle']];
        $executeAt = new \DateTime($value[$title['executeAtTitle']]);
        $posName = $value[$title['posNameTitle']];
        $posBalance = $value[$title['balanceTitle']];
        $msisdn = $value[$title['simTitle']];

        return compact('profile', 'executeAt', 'posName', 'posBalance', 'msisdn');
    }

    public function save($value)
    {
        $this->save->addBalance($value);
    }
}