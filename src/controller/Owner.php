<?php

namespace financas_api\controller;

use Exception;
use financas_api\model\bo\Owner as BoOwner;
use financas_api\model\entity\Response;
use TypeError;

class Owner
{
    public function create(array $parameters)
    {
        try {
            $name = isset($parameters['name']) ? $parameters['name'] : null;
            $active = isset($parameters['active']) ? $parameters['active'] : null;

            $bo = new BoOwner();
            $response = $bo->create($name, $active);
            // $response = $bo->create($parameters['name'], $parameters['active']);

            Response::send(['response' => $response], true, 200);
        } catch (Exception $th) {
            Response::send([
                'code' => $th->getCode(), 
                'message' => $th->getMessage(), 
            ], true, 404);
        } catch (\TypeError $te) {
            // print_r($te->getMessage());
            Response::send([
                'message' => 'data provided not accepted', 
                'details' => $te->getMessage(), 
            ], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function update(array $parameters)
    {
        try {
            // $id = isset($parameters['id']) ? $parameters['id'] : '';
            // $active = isset($parameters['active']) ? $parameters['active'] : true;

            $bo = new BoOwner();
            $response = $bo->update($parameters['id'], $parameters['active']);

            Response::send(['response' => $response], true, 200);
        } catch (Exception $th) {
            Response::send([
                'code' => $th->getCode(), 
                'message' => $th->getMessage(), 
            ], true, 404);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function delete(array $parameters)
    {
        try {
            if (!isset($parameters['id'])) 
                Response::send(['code' => 010100101, 'message' => 'Attribute \'id\' need to be informed'], true, 404);

            $bo = new BoOwner();
            $response = $bo->delete($parameters['id']);

            Response::send(['response' => $response], true, 200);
        } catch (Exception $th) {
            Response::send([
                'code' => $th->getCode(), 
                'message' => $th->getMessage(), 
            ], true, 404);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    public function find(array $parameters)
    {
        try {
            // $name = isset($parameters['name']) ? $parameters['name'] : '';
            // $active = isset($parameters['active']) ? $parameters['active'] : true;

            $bo = new BoOwner();
            $response = $bo->find($parameters['id']);

            Response::send(['response' => $response], true, 200);
        } catch (Exception $th) {
            Response::send([
                'code' => $th->getCode(), 
                'message' => $th->getMessage(), 
            ], true, 404);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }
}

?>