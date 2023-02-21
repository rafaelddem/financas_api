<?php

namespace financas_api\model\entity;

use DateTime;
use financas_api\conf\Parameters;
use financas_api\exceptions\DateCreateException;
use financas_api\exceptions\ValueNotAcceptException;

class CardDate
{
    private int $card_id;
    private DateTime $start_date;
    private DateTime $end_date;
    private float $value;

    public function __construct(int $card_id, string $start_date, string $end_date, float $value = 0.0)
    {
        self::setCardId($card_id);
        self::setStartDate($start_date);
        self::setEndDate($end_date);
        self::setValue($value);
    }

    private function setCardId(int $card_id)
    {
        $this->card_id = $card_id;
    }

    public function getCardId() : int
    {
        return $this->card_id;
    }

    private function setStartDate(string $start_date)
    {
        try {
            $this->start_date = new DateTime($start_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'start_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201008001);
        }
    }

    public function getStartDate() : string
    {
        return $this->start_date->format('Y-m-d');
    }

    private function setEndDate(string $end_date)
    {
        try {
            $this->end_date = new DateTime($end_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'end_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201008002);
        }

        if ($this->start_date >= $this->end_date) 
            throw new ValueNotAcceptException('The \'end_date\' need to be greater then \'start_date\'', 1201008003);
    }

    public function getEndDate() : string
    {
        return $this->end_date->format('Y-m-d');
    }

    private function setValue(float $value)
    {
        if ($value < 0.0) 
            throw new ValueNotAcceptException('The value for \'value\' need to be positive', 1201005004);

        $this->value = round($value, Parameters::DECIMAL_PRECISION);
    }

    public function getValue() : float
    {
        return round($this->value, Parameters::DECIMAL_PRECISION);
    }

    public function entityToArray() : array
    {
        return [
            'card_id' => $this->getCardId(), 
            'start_date' => $this->getStartDate(), 
            'end_date' => $this->getEndDate(), 
            'value' => $this->getValue(), 
        ];
    }

}
    
?>