<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Transaction as Transaction_dataAccess;
use financas_api\model\entity\Transaction as Transaction_entity;

class Transaction
{
    private $id;
    private $tittle;
    private $transaction_date;
    private $transaction_type;
    private $gross_value;
    private $discount_value;
    private $relevance;
    private $description;

    public function __construct(array $parameters = null)
    {
        $this->id = isset($parameters['id']) ? $parameters['id'] : null;
        $this->tittle = isset($parameters['tittle']) ? $parameters['tittle'] : null;
        $this->transaction_date = isset($parameters['transaction_date']) ? $parameters['transaction_date'] : null;
        $this->transaction_type = isset($parameters['transaction_type']) ? $parameters['transaction_type'] : null;
        $this->gross_value = isset($parameters['gross_value']) ? $parameters['gross_value'] : null;
        $this->discount_value = isset($parameters['discount_value']) ? $parameters['discount_value'] : null;
        $this->relevance = isset($parameters['relevance']) ? $parameters['relevance'] : null;
        $this->description = isset($parameters['description']) ? $parameters['description'] : null;
    }

    public function create()
    {
        try {
            $transaction = new Transaction_entity(0, $this->tittle, $this->transaction_date, $this->transaction_type, $this->gross_value, $this->discount_value, $this->installments, $this->relevance, $this->description);
            $dao = new Transaction_dataAccess();

            Response::send(['response' => $dao->insert($transaction)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function update()
    {
        try {
            $transaction = self::findEntity();

            $hasUpdate = false;
            if (isset($this->active)) {
                $transaction->setActive($this->active);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203001001);

            $dao = new Transaction_dataAccess();
            Response::send(['message' => $dao->update($transaction)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function delete()
    {
        try {
            $dao = new Transaction_dataAccess();
            Response::send(['message' => $dao->delete($this->id)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function find()
    {
        try {
            // if (isset($this->active)) {
            //     $transaction = self::findEntityByActive();
            // } else {
                $transaction = self::findEntity();
                $transaction = $transaction->entityToJson();
            // }

            Response::send(['response' => $transaction], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function findAll()
    {
        try {
            $dao = new Transaction_dataAccess();
            $transactions = $dao->findAll();

            Response::send(['response' => $transactions], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    // private function findEntityByActive()
    // {
    //     $dao = new Transaction_dataAccess();
    //     return $dao->findAllByActive($this->active);
    // }

    private function findEntity()
    {
        $dao = new Transaction_dataAccess();
        return $dao->find($this->id);
    }

}
    
?>