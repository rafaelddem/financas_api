<?php

namespace financas_api\model\dataAccess;

use Exception;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\model\entity\Owner as Owner_entity;
use \PDO;
use PDOException;

class Owner extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(Owner_entity $owner)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into owner (name, active) values (:name, :active);");
            $name = $owner->getName();
            $active = $owner->getActive();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while creating an \'owner\'. Please inform support', 1202001001);
            }

            self::getPDO()->commit();
            return '\'Owner\' successfully created';
        } catch (PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202001012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while creating an \'owner\'. Please inform support', 1202001002);
        }
    }

    public function update(Owner_entity $owner)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update owner set active = :active where id = :id");
            $active = $owner->getActive();
            $id = $owner->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while updating an \'owner\'. Please inform support', 1202001003);
            }

            self::getPDO()->commit();
            return '\'Owner\' successfully updated';
        } catch (PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202001013);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while updating an \'owner\'. Please inform support', 1202001004);
        }
    }

    public function delete(int $id)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from owner where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while deleting an \'owner\'. Please inform support', 1202001005);
            }

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202001007);

            self::getPDO()->commit();
            return '\'Owner\' successfully deleted';
        } catch (PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202001014);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while deleting an \'owner\'. Please inform support', 1202001010);
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

            $sql  = "select * from owner $where";
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

            $owners = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $owner = new Owner_entity($row->id, $row->name, boolval($row->active));
                    $owners[] = $convertJson ? $owner->entityToJson() : $owner;
                }
            }

            return $owners;
        } catch (\Throwable $th) {
            throw new Exception('An error occurred while looking for an \'owner\'. Please inform support', 1202001011);
        }
    }
}
    
?>