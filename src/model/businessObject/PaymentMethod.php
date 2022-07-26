<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\PaymentMethod as PaymentMethod_dataAccess;
use financas_api\model\entity\PaymentMethod as PaymentMethod_entity;

class PaymentMethod
{
    private $id;
    private $name;
    private $active;

    public function __construct(array $parameters = null)
    {
        $this->id = isset($parameters['id']) ? $parameters['id'] : null;
        $this->name = isset($parameters['name']) ? $parameters['name'] : null;
        $this->active = isset($parameters['active']) ? ($parameters['active'] == 'true' OR $parameters['active'] == 1) : null;
    }

    public function create()
    {
        try {
            $paymentMethod = new PaymentMethod_entity(0, $this->name, $this->active);
            $dao = new PaymentMethod_dataAccess();

            Response::send(['response' => $dao->insert($paymentMethod)], true, 200);
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
            $paymentMethod = self::findEntity();

            $hasUpdate = false;
            if (isset($this->active)) {
                $paymentMethod->setActive($this->active);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203003001);

            $dao = new PaymentMethod_dataAccess();
            Response::send(['message' => $dao->update($paymentMethod)], true, 200);
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
            $dao = new PaymentMethod_dataAccess();
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
        if (isset($this->id)) {
            self::findById();
        } else {
            self::findAll();
        }
    }

    public function findById()
    {
        try {
            $paymentMethod = self::findEntity();
            $paymentMethod = $paymentMethod->entityToJson();

            Response::send(['response' => $paymentMethod], true, 200);
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
            $dao = new PaymentMethod_dataAccess();
            $paymentMethods = $dao->findAll();

            Response::send(['response' => $paymentMethods], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function findEntity()
    {
        $dao = new PaymentMethod_dataAccess();
        return $dao->find($this->id);
    }

}
    
?>