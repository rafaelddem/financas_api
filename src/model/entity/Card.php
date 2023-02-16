<?php

namespace financas_api\model\entity;

use financas_api\exceptions\ValueNotAcceptException;

class Card
{
    private int $id;
    private int $wallet_id;
    private string $name;
    private bool $allow_credit;
    private int $first_day_month;
    private int $days_to_expiration;
    private bool $active;

    public function __construct(int $id, int $wallet_id, string $name, bool $active, bool $allow_credit, int $first_day_month = 0, int $days_to_expiration = 0)
    {
        self::setId($id);
        self::setWalletId($wallet_id);
        self::setName($name);
        self::setActive($active);
        self::setAllowCredit($allow_credit);
        self::setFirstDayMonth($first_day_month);
        self::setDaysToExpiration($days_to_expiration);
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }

    private function setWalletId(int $wallet_id)
    {
        $this->wallet_id = $wallet_id;
    }

    public function getWalletId() : int
    {
        return $this->wallet_id;
    }

    public function setName(string $name)
    {
        if(strlen($name) < 3 OR strlen($name) > 20)
            throw new ValueNotAcceptException('The \'name\' attribute need to be between 3 and 20 characters', 1201007001);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $name))
            throw new ValueNotAcceptException('The \'name\' attribute only accepts letters and numbers', 1201007002);
        
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getActive() : bool
    {
        return $this->active;
    }

    private function setAllowCredit(bool $allow_credit)
    {
        $this->allow_credit = $allow_credit;
    }

    public function getAllowCredit() : bool
    {
        return $this->allow_credit;
    }

    public function setFirstDayMonth(int $first_day_month)
    {
        if ($first_day_month < 0 OR $first_day_month > 28) 
            throw new ValueNotAcceptException('The attribute \'first_day_month\' need to be between 1 and 28 (or 0 for debit)', 1201007003);

        $this->first_day_month = $first_day_month;
    }

    public function getFirstDayMonth() : int
    {
        return $this->first_day_month;
    }

    public function setDaysToExpiration(int $days_to_expiration)
    {
        $this->days_to_expiration = $days_to_expiration;
    }

    public function getDaysToExpiration() : int
    {
        return $this->days_to_expiration;
    }

    public function entityToArray() : array
    {
        return [
            'id' => $this->getId(), 
            'wallet_id' => $this->getWalletId(), 
            'name' => $this->getName(), 
            'active' => $this->getActive(), 
            'allow_credit' => getAllowCredit(), 
            'first_day_month' => $this->getFirstDayMonth(), 
            'days_to_expiration' => $this->getDaysToExpiration(), 
        ];
    }

}
    
?>