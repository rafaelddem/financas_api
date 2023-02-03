<?php

namespace financas_api\model\businessObject;

use api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Installment as Installment_dataAccess;
use financas_api\model\dataAccess\Transaction as Transaction_dataAccess;
use financas_api\model\entity\Installment as Installment_entity;
use financas_api\model\entity\Transaction as Transaction_entity;

class Installment
{
    private $transaction;
    private $installment_number;
    private $discount_value;
    private $interest_value;
    private $rounding_value;
    private $source_wallet;
    private $payment_method;
    private $payment_date;

    public function __construct(array $parameters = null)
    {
        $this->transaction = isset($parameters['transaction']) ? $parameters['transaction'] : null;
        $this->installment_number = isset($parameters['installment_number']) ? $parameters['installment_number'] : null;
        $this->discount_value = isset($parameters['discount_value']) ? $parameters['discount_value'] : null;
        $this->interest_value = isset($parameters['interest_value']) ? $parameters['interest_value'] : null;
        $this->rounding_value = isset($parameters['rounding_value']) ? $parameters['rounding_value'] : null;
        $this->source_wallet = isset($parameters['source_wallet']) ? $parameters['source_wallet'] : null;
        $this->payment_method = isset($parameters['payment_method']) ? $parameters['payment_method'] : null;
        $this->payment_date = isset($parameters['payment_date']) ? $parameters['payment_date'] : null;
    }

    public function update()
    {
        try {
            $installment = self::findEntity();

            $hasUpdate = false;
            if (isset($this->discount_value)) {
                $installment->setDiscountValue($this->discount_value);
                $hasUpdate = true;
            }
            if (isset($this->interest_value)) {
                $installment->setInterestValue($this->interest_value);
                $hasUpdate = true;
            }
            if (isset($this->rounding_value)) {
                $installment->setRoundingValue($this->rounding_value);
                $hasUpdate = true;
            }
            if (isset($this->source_wallet)) {
                $installment->setSourceWallet($this->source_wallet);
                $hasUpdate = true;
            }
            if (isset($this->payment_method)) {
                $installment->setPaymentMethod($this->payment_method);
                $hasUpdate = true;
            }
            if (isset($this->payment_date)) {
                $installment->setPaymentDate($this->payment_date);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203006001);

            $dao = new Installment_dataAccess();
            Response::send(['response' => $dao->update($installment)], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function findEntity() : Installment_entity
    {
        if (isset($this->transaction) < 1) 
            throw new EmptyValueException('You need inform \'transaction id\'', 1203005002);

        if (isset($this->installment_number) < 1) 
            throw new EmptyValueException('You need inform \'installment number\'', 1203005002);

        $dao = new Installment_dataAccess();
        $installments = $dao->findByFilter([
            'transaction' => $this->transaction, 
            'installment_number' => $this->installment_number, 
        ], false);

        if (count($installments) < 1) 
            throw new DataNotExistException('There are no data for the informed request', 1203005003);

        return $installments[0];
    }

    public function find()
    {
        try {
            if (isset($this->transaction) < 1) 
                throw new EmptyValueException('You need inform \'transaction id\'', 1203005002);
    
            if (isset($this->installment_number) < 1) 
                throw new EmptyValueException('You need inform \'installment number\'', 1203005002);
    
            $dao = new Installment_dataAccess();
            $installments = $dao->findByFilter([
                'transaction' => $this->transaction, 
                'installment_number' => $this->installment_number, 
            ], true);
    
            if (count($installments) < 1) 
                throw new DataNotExistException('There are no data for the informed request', 1203005003);
    
            $installment = $installments[0];

            Response::send(['response' => $installment], true, 200);
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