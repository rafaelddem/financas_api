<?php

namespace financas_api\model\businessObject;

use api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\CardDate as CardDate_dataAccess;
use financas_api\model\entity\CardDate as CardDate_entity;

class CardDate
{
    private $card_id;
    private $start_date;
    private $end_date;

    public function __construct(array $parameters = null)
    {
        $this->card_id = isset($parameters['card_id']) ? $parameters['card_id'] : null;
        $this->start_date = isset($parameters['start_date']) ? $parameters['start_date'] : null;
        $this->end_date = isset($parameters['end_date']) ? $parameters['end_date'] : null;
    }

    public function create()
    {
        try {
            $cardDate = new CardDate_entity($this->card_id, $this->start_date, $this->end_date);
            $dao = new CardDate_dataAccess();

            Response::send(['response' => $dao->insert($cardDate)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function delete()
    {
        try {
            $dao = new CardDate_dataAccess();
            Response::send(['response' => $dao->delete($this->card_id)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function findEntity(bool $convertJson = false)
    {
        if (isset($this->card_id) || isset($this->start_date) || isset($this->end_date)) 
            throw new EmptyValueException('You need inform all data from register', 1203007002);

        $dao = new CardDate_dataAccess();
        $cardDate = $dao->findByFilter([
            'card_id' => $this->card_id, 
            'start_date' => $this->start_date, 
            'end_date' => $this->end_date, 
        ], $convertJson);

        if (count($cardDate) < 1) 
            throw new DataNotExistException('There are no data for this \'card_id\'', 1203007003);

        return $cardDate[0];
    }

}

?>