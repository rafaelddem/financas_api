<?php

namespace financas_api\model\businessObject;

use api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Card as Card_dataAccess;
use financas_api\model\entity\Card as Card_entity;

class Card
{
    private $id;
    private $wallet_id;
    private $name;
    private $first_day_month;
    private $days_to_expiration;
    private $active;

    public function __construct(array $parameters = null)
    {
        $this->id = isset($parameters['id']) ? $parameters['id'] : null;
        $this->wallet_id = isset($parameters['wallet_id']) ? $parameters['wallet_id'] : null;
        $this->name = isset($parameters['name']) ? $parameters['name'] : null;
        $this->first_day_month = isset($parameters['first_day_month']) ? $parameters['first_day_month'] : null;
        $this->days_to_expiration = isset($parameters['days_to_expiration']) ? $parameters['days_to_expiration'] : null;
        $this->active = isset($parameters['active']) ? ($parameters['active'] == 'true' OR $parameters['active'] == 1) : null;
    }

    public function create()
    {
        try {
            $card = new Card_entity(0, $this->wallet_id, $this->name, $this->first_day_month, $this->days_to_expiration, $this->active);
            $dao = new Card_dataAccess();

            Response::send(['response' => $dao->insert($card)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function update()
    {
        try {
            $card = self::findEntity();

            $hasUpdate = false;
            if (isset($this->name)) {
                $card->setName($this->name);
                $hasUpdate = true;
            }
            if (isset($this->first_day_month)) {
                $card->setFirstDayMonth($this->first_day_month);
                $hasUpdate = true;
            }
            if (isset($this->days_to_expiration)) {
                $card->setDaysToExpiration($this->days_to_expiration);
                $hasUpdate = true;
            }
            if (isset($this->active)) {
                $card->setActive($this->active);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203007001);

            $dao = new Card_dataAccess();
            Response::send(['response' => $dao->update($card)], true, 200);
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
            $dao = new Card_dataAccess();
            Response::send(['response' => $dao->delete($this->id)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function findEntity()
    {
        if (isset($this->id) < 1) 
            throw new EmptyValueException('You need inform the \'id\'', 1203007002);

        $dao = new Card_dataAccess();
        $cards = $dao->findByFilter([
            'id' => $this->id, 
        ], false);

        if (count($cards) < 1) 
            throw new DataNotExistException('There are no data for this \'id\'', 1203007003);

        return $cards[0];
    }

    public function find()
    {
        try {
            $dao = new Card_dataAccess();
            $cards = $dao->findByFilter([
                'id' => $this->id, 
                'wallet_id' => $this->wallet_id, 
                'name' => $this->name, 
                'first_day_month' => $this->first_day_month, 
                'days_to_expiration' => $this->days_to_expiration, 
                'active' => $this->active, 
            ]);

            Response::send(['response' => $cards], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

}

?>