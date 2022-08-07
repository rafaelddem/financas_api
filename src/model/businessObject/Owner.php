<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Owner as Owner_dataAccess;
use financas_api\model\entity\Owner as Owner_entity;

class Owner
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
            $owner = new Owner_entity(0, $this->name, $this->active);
            $dao = new Owner_dataAccess();

            Response::send(['response' => $dao->insert($owner)], true, 200);
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
            $owner = self::findEntity();

            $hasUpdate = false;
            if (isset($this->active)) {
                $owner->setActive($this->active);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203001001);

            $dao = new Owner_dataAccess();
            Response::send(['message' => $dao->update($owner)], true, 200);
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
            $dao = new Owner_dataAccess();
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
        $dao = new Owner_dataAccess();
        $owners = $dao->findByFilter([
            'id' => $this->id, 
        ], false);

        if (count($owners) < 1) 
            throw new DataNotExistException('There are no data for this \'id\'', 1203001002);

        return $owners[0];
    }

    public function find()
    {
        try {
            $dao = new Owner_dataAccess();
            $owners = $dao->findByFilter([
                'id' => $this->id, 
                'name' => $this->name, 
                'active' => $this->active, 
            ]);

            Response::send(['response' => $owners], true, 200);
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