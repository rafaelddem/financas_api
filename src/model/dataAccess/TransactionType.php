<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\TransactionType as TransactionType_entity;
use \PDO;

class TransactionType extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(TransactionType_entity $transactionType)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into transaction_type (name, relevance, active) values (:name, :relevance, :active);");
            $name = $transactionType->getName();
            $relevance = $transactionType->getRelevance();
            $active = $transactionType->getActive();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202004001);

            self::getPDO()->commit();
            return '\'Transaction type\' successfully created';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202004012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'transaction type\'. Please inform support', 1202004002);
        }
    }

    public function update(TransactionType_entity $transactionType)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update transaction_type set active = :active, relevance = :relevance where id = :id");
            $active = $transactionType->getActive();
            $relevance = $transactionType->getRelevance();
            $id = $transactionType->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202004003);

            self::getPDO()->commit();
            return '\'Transaction type\' successfully updated';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202004013);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'transaction type\'. Please inform support', 1202004004);
        }
    }

    public function delete(int $id)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from transaction_type where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202004005);

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202004006);

            self::getPDO()->commit();
            return '\'Transaction type\' successfully deleted';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202004007);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while deleting an \'transaction type\'. Please inform support', 1202004010);
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
            if (isset($filters['relevance'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " relevance = :relevance";
            }
            if (isset($filters['active'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " active = :active";
            }

            $sql  = "select * from transaction_type $where";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['id'])) {
                $id = $filters['id'];
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            if (isset($filters['name'])) {
                $name = '%' . $filters['name'] . '%';
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            if (isset($filters['relevance'])) {
                $relevance = $filters['relevance'];
                $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            }
            if (isset($filters['active'])) {
                $active = $filters['active'];
                $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            }

            $transactionTypes = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $transactionType = new TransactionType_entity($row->id, $row->name, $row->relevance, boolval($row->active));
                    $transactionTypes[] = $convertJson ? $transactionType->entityToArray() : $transactionType;
                }
            }

            return $transactionTypes;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'transaction type\'. Please inform support', 1202004011);
        }
    }

}
    
?>