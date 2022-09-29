<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;
use financas_api\model\dataAccess\Wallet as Wallet_dataAccess;
use financas_api\model\entity\Wallet as Wallet_entity;

class Wallet
{
    private $id;
    private $name;
    private $owner_id;
    private $main_wallet;
    private $active;
	private $description;

    public function __construct(array $parameters = null)
    {
        $this->id = isset($parameters['id']) ? $parameters['id'] : null;
        $this->name = isset($parameters['name']) ? $parameters['name'] : null;
        $this->owner_id = isset($parameters['owner_id']) ? $parameters['owner_id'] : null;
        $this->main_wallet = isset($parameters['main_wallet']) ? ($parameters['main_wallet'] == 'true' OR $parameters['main_wallet'] == 1) : null;
        $this->active = isset($parameters['active']) ? ($parameters['active'] == 'true' OR $parameters['active'] == 1) : null;
        $this->description = isset($parameters['description']) ? $parameters['description'] : null;
    }

    public function create()
    {
        try {
            $description = isset($this->description) ? $this->description : '';
            $wallet = new Wallet_entity(0, $this->name, $this->owner_id, $this->main_wallet, $this->active, $description);
            $dao = new Wallet_dataAccess();

            Response::send(['response' => $dao->insert($wallet)], true, 200);
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
            $wallet = self::findEntity();

            $hasUpdate = false;
            if (isset($this->main_wallet)) {
                $wallet->setMainWallet($this->main_wallet);
                $hasUpdate = true;
            }
            if (isset($this->active)) {
                $wallet->setActive($this->active);
                $hasUpdate = true;
            }
            if (isset($this->description)) {
                $wallet->setDescription($this->description);
                $hasUpdate = true;
            }

            if (!$hasUpdate) 
                throw new ValueNotAcceptException('Parameters must be informed for the update', 1203002001);

            $dao = new Wallet_dataAccess();
            Response::send(['response' => $dao->update($wallet)], true, 200);
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
            $dao = new Wallet_dataAccess();
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
            throw new EmptyValueException('You need inform the \'id\'', 1203002002);

        $dao = new Wallet_dataAccess();
        $wallets = $dao->findByFilter([
            'id' => $this->id, 
        ], false);

        if (count($wallets) < 1) 
            throw new DataNotExistException('There are no data for this \'id\'', 1203002003);

        return $wallets[0];
    }

    public function find()
    {
        try {
            $dao = new Wallet_dataAccess();
            $wallets = $dao->findByFilter([
                'id' => $this->id, 
                'name' => $this->name, 
                'owner_id' => $this->owner_id, 
                'main_wallet' => $this->main_wallet, 
                'active' => $this->active, 
                'description' => $this->description, 
            ]);

            Response::send(['response' => $wallets], true, 200);
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