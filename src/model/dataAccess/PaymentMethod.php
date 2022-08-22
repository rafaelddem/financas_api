<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\PaymentMethod as PaymentMethod_entity;
use \PDO;

class PaymentMethod extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(PaymentMethod_entity $paymentMethod)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into payment_method (name, active) values (:name, :active);");
            $name = $paymentMethod->getName();
            $active = $paymentMethod->getActive();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202003001);

            self::getPDO()->commit();
            return '\'Payment method\' successfully created';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202003012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'payment method\'. Please inform support', 1202003002);
        }
    }

    public function update(PaymentMethod_entity $paymentMethod)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update payment_method set active = :active where id = :id");
            $active = $paymentMethod->getActive();
            $id = $paymentMethod->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202003003);

            self::getPDO()->commit();
            return '\'Payment method\' successfully updated';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202003013);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'payment method\'. Please inform support', 1202003004);
        }
    }

    public function delete(int $id)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from payment_method where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202003005);

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202003006);

            self::getPDO()->commit();
            return '\'Payment method\' successfully deleted';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202003007);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while deleting an \'payment method\'. Please inform support', 1202003010);
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
            if (isset($filters['active'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " active = :active";
            }

            $sql  = "select * from payment_method $where";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['id'])) {
                $id = $filters['id'];
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            if (isset($filters['name'])) {
                $name = '%' . $filters['name'] . '%';
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            if (isset($filters['active'])) {
                $active = $filters['active'];
                $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            }

            $paymentMethods = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $paymentMethod = new PaymentMethod_entity($row->id, $row->name, boolval($row->active));
                    $paymentMethods[] = $convertJson ? $paymentMethod->entityToJson() : $paymentMethod;
                }
            }

            return $paymentMethods;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'payment method\'. Please inform support', 1202003011);
        }
    }

}
    
?>