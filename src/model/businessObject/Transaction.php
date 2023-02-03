<?php

namespace financas_api\model\businessObject;

use api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Transaction as Transaction_dataAccess;
use financas_api\model\entity\Installment as Installment_entity;
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
    private $installments;

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

        $json_installments = isset($parameters['installments']) ? $parameters['installments'] : array();

        foreach ($json_installments as $json_installment) {
            $installment = array();
            $installment['transaction'] = isset($json_installment['transaction']) ? $json_installment['transaction'] : null;
            $installment['installment_number'] = isset($json_installment['installment_number']) ? $json_installment['installment_number'] : null;
            $installment['due_date'] = isset($json_installment['due_date']) ? $json_installment['due_date'] : null;
            $installment['gross_value'] = isset($json_installment['gross_value']) ? $json_installment['gross_value'] : null;
            $installment['discount_value'] = isset($json_installment['discount_value']) ? $json_installment['discount_value'] : null;
            $installment['interest_value'] = isset($json_installment['interest_value']) ? $json_installment['interest_value'] : null;
            $installment['rounding_value'] = isset($json_installment['rounding_value']) ? $json_installment['rounding_value'] : null;
            $installment['payment_date'] = isset($json_installment['payment_date']) ? $json_installment['payment_date'] : null;
            $installment['payment_method'] = isset($json_installment['payment_method']) ? $json_installment['payment_method'] : null;
            $installment['source_wallet'] = isset($json_installment['source_wallet']) ? $json_installment['source_wallet'] : null;
            $installment['destination_wallet'] = isset($json_installment['destination_wallet']) ? $json_installment['destination_wallet'] : null;
            $this->installments[] = $installment;
        }
    }

    public function create()
    {
        try {
            $resultMessage = self::createEntity();
            Response::send(['response' => $resultMessage], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function createEntity()
    {
        $installments = array();
        foreach ($this->installments as $installment) {
            $installments[] = new Installment_entity(0, $installment['installment_number'], $installment['due_date'], 
                $installment['gross_value'], $installment['discount_value'], $installment['interest_value'], $installment['rounding_value'], 
                $installment['destination_wallet'], $installment['source_wallet'], $installment['payment_method'], $installment['payment_date']);
        }
        $transaction = new Transaction_entity(0, $this->tittle, $this->transaction_date, $this->transaction_type, $this->gross_value, $this->discount_value, $installments, $this->relevance, $this->description);
        $dao = new Transaction_dataAccess();

        return $dao->insert($transaction);
    }

    public function update()
    {
        if (self::checksIfRequestsChangeOfValuesAndDates()) {
            self::updateTransactionSensitiveData();
        } else {
            self::updateTransactionNonSensitiveData();
        }
    }

    private function updateTransactionSensitiveData()
    {
        try {
            $transaction = self::findEntity();
            $resultMessage = self::createEntity();
            $resultMessage = self::deleteEntity();

            $dao = new Transaction_dataAccess();
            Response::send(['response' => '\'Transaction\' successfully updated'], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function updateTransactionNonSensitiveData()
    {
        try {
            $transaction = self::findEntity();

            $hasUpdate = false;
            if (isset($this->tittle)) {
                $transaction->setTittle($this->tittle);
                $hasUpdate = true;
            }
            if (isset($this->transaction_type)) {
                $transaction->setTransactionType($this->transaction_type);
                $hasUpdate = true;
            }
            if (isset($this->relevance)) {
                $transaction->setRelevance($this->relevance);
                $hasUpdate = true;
            }
            if (isset($this->description)) {
                $transaction->setDescription($this->description);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203005001);

            $dao = new Transaction_dataAccess();
            Response::send(['response' => $dao->update($transaction)], true, 200);
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
            $resultMessage = self::deleteEntity();
            Response::send(['response' => $resultMessage], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function deleteEntity() : string
    {
        $transaction = self::findEntity();

        $dao = new Transaction_dataAccess();
        return $dao->delete($transaction->getId());
    }

    private function findEntity() : Transaction_entity
    {
        if (isset($this->id) < 1) 
            throw new EmptyValueException('You need inform the \'id\'', 1203005002);

        $dao = new Transaction_dataAccess();
        $transactions = $dao->findByFilter([
            'id' => $this->id, 
        ], false);

        if (count($transactions) < 1) 
            throw new DataNotExistException('There are no data for this \'id\'', 1203005003);

        return $transactions[0];
    }

    public function find()
    {
        try {
            $dao = new Transaction_dataAccess();
            $transactions = $dao->findByFilter([
                'id' => $this->id, 
                'tittle' => $this->tittle, 
                'transaction_date' => $this->transaction_date, 
                'transaction_type' => $this->transaction_type, 
                'relevance' => $this->relevance, 
                'description' => $this->description, 
            ]);

            Response::send(['response' => $transactions], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function checksIfRequestsChangeOfValuesAndDates()
    {
        $changeNecessary = false;

        if (isset($this->transaction_date)) {
            $changeNecessary = true;
        }
        if (isset($this->gross_value)) {
            $changeNecessary = true;
        }
        if (isset($this->discount_value)) {
            $changeNecessary = true;
        }
        if (isset($this->installments)) {
            $changeNecessary = true;
        }

        return $changeNecessary;
    }

}
    
?>