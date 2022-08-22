<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
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
        if (!self::getPDO()->inTransaction()) 
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

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202002001);

            self::getPDO()->commit();
            return '\'Wallet\' successfully created';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202002012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'wallet\'. Please inform support', 1202002002);
        }
    }

    public function update(Wallet_entity $wallet)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update wallet set active = :active, main_wallet = :main_wallet where id = :id");
            $active = $wallet->getActive();
            $main_wallet = $wallet->getMainWallet();
            $id = $wallet->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':main_wallet', $main_wallet, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202002003);

            self::getPDO()->commit();
            return '\'Wallet\' successfully updated';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202002013);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'wallet\'. Please inform support', 1202002004);
        }
    }

    public function delete(int $id)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from wallet where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202002005);

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202002006);

            self::getPDO()->commit();
            return '\'Wallet\' successfully deleted';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202002007);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while deleting an \'wallet\'. Please inform support', 1202002010);
        }
    }

    public function findByFilter(array $filters, bool $convertJson = true)
    {
        try {
            $where = "";
            if (isset($filters['id'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " id = :id";
            }
            if (isset($filters['name'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " name like :name";
            }
            if (isset($filters['owner_id'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " owner_id = :owner_id";
            }
            if (isset($filters['main_wallet'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " main_wallet = :main_wallet";
            }
            if (isset($filters['active'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " active = :active";
            }

            $sql  = "select * from wallet $where";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['id'])) {
                $id = $filters['id'];
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            if (isset($filters['name'])) {
                $name = '%' . $filters['name'] . '%';
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            if (isset($filters['owner_id'])) {
                $owner_id = $filters['owner_id'];
                $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
            }
            if (isset($filters['main_wallet'])) {
                $main_wallet = $filters['main_wallet'];
                $stmt->bindParam(':main_wallet', $main_wallet, PDO::PARAM_BOOL);
            }
            if (isset($filters['active'])) {
                $active = $filters['active'];
                $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            }

            $wallets = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $wallet = new Wallet_entity($row->id, $row->name, $row->owner_id, boolval($row->main_wallet), boolval($row->active));
                    $wallets[] = $convertJson ? $wallet->entityToJson() : $wallet;
                }
            }

            return $wallets;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }

}
    
?>