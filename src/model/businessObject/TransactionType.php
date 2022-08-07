<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\TransactionType as TransactionType_dataAccess;
use financas_api\model\entity\TransactionType as TransactionType_entity;

class TransactionType
{
    private $id;
    private $name;
    private $relevance;
    private $active;

    public function __construct(array $parameters = null)
    {
        $this->id = isset($parameters['id']) ? $parameters['id'] : null;
        $this->name = isset($parameters['name']) ? $parameters['name'] : null;
        $this->relevance = isset($parameters['relevance']) ? $parameters['relevance'] : null;
        $this->active = isset($parameters['active']) ? ($parameters['active'] == 'true' OR $parameters['active'] == 1) : null;
    }

    public function create()
    {
        try {
            $transactionType = new TransactionType_entity(0, $this->name, $this->relevance, $this->active);
            $dao = new TransactionType_dataAccess();

            Response::send(['response' => $dao->insert($transactionType)], true, 200);
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
            $transactionType = self::findEntity();

            $hasUpdate = false;
            if (isset($this->active)) {
                $transactionType->setActive($this->active);
                $hasUpdate = true;
            }
            if (isset($this->relevance)) {
                $transactionType->setRelevance($this->relevance);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203004001);

            $dao = new TransactionType_dataAccess();
            Response::send(['message' => $dao->update($transactionType)], true, 200);
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
            $dao = new TransactionType_dataAccess();
            Response::send(['message' => $dao->delete($this->id)], true, 200);
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
        $dao = new TransactionType_dataAccess();
        $transactionTypes = $dao->findByFilter([
            'id' => $this->id, 
        ], false);

        if (count($transactionTypes) < 1) 
            throw new DataNotExistException('There are no data for this \'id\'', 1203004002);

        return $transactionTypes[0];
    }

    public function find()
    {
        try {
            $dao = new TransactionType_dataAccess();
            $transactionTypes = $dao->findByFilter([
                'id' => $this->id, 
                'name' => $this->name, 
                'relevance' => $this->relevance, 
                'active' => $this->active, 
            ]);

            Response::send(['response' => $transactionTypes], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

}
    
?>