<?php

namespace financas_api\model\entity;

use DateTime;
use financas_api\exceptions\EmptyValueException;

class CardDate
{
    private int $card_id;
    private DateTime $start_date;
    private DateTime $end_date;

    public function __construct(int $card_id, string $start_date, string $end_date)
    {
        self::setCardId($card_id);
        self::setStartDate($start_date);
        self::setEndDate($end_date);
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
        if ($start_date == '') 
            throw new EmptyValueException('The value for \'start_date\' need to be informed', 1201008001);

        try {
            $this->start_date = new DateTime($start_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'start_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201008002);
        }
    }

    public function getStartDate() : string
    {
        return $this->start_date->format('Y-m-d');
    }

    private function setEndDate(string $end_date)
    {
        if ($end_date == '') 
            throw new EmptyValueException('The value for \'end_date\' need to be informed', 1201008003);

        try {
            $this->end_date = new DateTime($end_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'end_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201008004);
        }
    }

    public function getEndDate() : string
    {
        return $this->end_date->format('Y-m-d');
    }

    public function entityToArray() : array
    {
        return [
            'card_id' => $this->getCardId(), 
            'start_date' => $this->getStartDate(), 
            'end_date' => $this->getEndDate(), 
        ];
    }

}
    
?>