<?php

namespace financas_api\model\dataAccess;

use Exception;
use financas_api\exceptions\DataNotExistException;
use financas_api\model\entity\Wallet as Wallet_entity;
use \PDO;

class Wallet extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(Wallet_entity $wallet)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into wallet (name, owner_id, main_wallet, active) values (:name, :owner_id, :main_wallet, :active)");
            $name = $wallet->getName();
            $owner_id = $wallet->getOwnerId();
            $main_wallet = $wallet->getMainWallet();
            $active = $wallet->getActive();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
            $stmt->bindParam(':main_wallet', $main_wallet, PDO::PARAM_BOOL);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while creating an \'wallet\'. Please inform support', 1202002001);
            }

            self::getPDO()->commit();
            return '\'Wallet\' successfully created';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while creating an \'wallet\'. Please inform support', 1202002002);
        }
    }

    public function update(Wallet_entity $wallet)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update wallet set active = :active, main_wallet = :main_wallet where id = :id");
            $active = $wallet->getActive();
            $main_wallet = $wallet->getMainWallet();
            $id = $wallet->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':main_wallet', $main_wallet, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while updating an \'wallet\'. Please inform support', 1202002003);
            }

            self::getPDO()->commit();
            return '\'Wallet\' successfully updated';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while updating an \'wallet\'. Please inform support', 1202002004);
        }
    }

    public function delete(int $id)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from wallet where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while deleting an \'wallet\'. Please inform support', 1202002005);
            }

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202002007);

            self::getPDO()->commit();
            return '\'Wallet\' successfully deleted';
        } catch (DataNotExistException $ex) {
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while deleting an \'wallet\'. Please inform support', 1202002010);
        }
    }

    public function find(int $id)
    {
        try {
            $stmt = self::getPDO()->prepare("select * from wallet where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $wallet = '';
            if ($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $wallet = new Wallet_entity($row->id, $row->name, $row->owner_id, boolval($row->main_wallet), boolval($row->active));
                    }
                } else {
                    throw new DataNotExistException('There are no data for this \'id\'', 1202002011);
                }
            }

            return $wallet;
        } catch (DataNotExistException $ex) {
            throw $ex;
        } catch (\Throwable $th) {
            throw new Exception('An error occurred while looking for an \'wallet\'. Please inform support', 1202002012);
        }
    }

    public function findAll()
    {
        try {
            $stmt = self::getPDO()->prepare("select * from wallet");

            $wallets = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $wallet = new Wallet_entity($row->id, $row->name, $row->owner_id, boolval($row->main_wallet), boolval($row->active));
                    $wallets[] = $wallet->entityToJson();
                }
            }

            return $wallets;
        } catch (DataNotExistException $ex) {
            throw $ex;
        } catch (\Throwable $th) {
            throw new Exception('An error occurred while looking for an \'Wallet\'. Please inform support', 1202002012);
        }
    }

}
    
?>