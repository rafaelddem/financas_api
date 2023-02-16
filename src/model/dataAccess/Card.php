<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\Card as Card_entity;
use \PDO;

class Card extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(Card_entity $card)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into card (wallet_id, name, allow_credit, first_day_month, days_to_expiration, active) values (:wallet_id, :name, :allow_credit, :first_day_month, :days_to_expiration, :active);");
            $wallet_id = $card->getWalletId();
            $name = $card->getName();
            $allow_credit = $card->getAllowCredit();
            $first_day_month = empty($card->getFirstDayMonth()) ? null : $card->getFirstDayMonth();
            $days_to_expiration = empty($card->getDaysToExpiration()) ? null : $card->getDaysToExpiration();
            $active = $card->getActive();
            $stmt->bindParam(':wallet_id', $wallet_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':allow_credit', $allow_credit, PDO::PARAM_BOOL);
            $stmt->bindParam(':first_day_month', $first_day_month, PDO::PARAM_INT);
            $stmt->bindParam(':days_to_expiration', $days_to_expiration, PDO::PARAM_INT);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202007001);

            self::getPDO()->commit();
            return '\'Card\' successfully created';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202007012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'card\'. Please inform support', 1202007002, $th);
        }
    }

    public function update(Card_entity $card)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update card set name = :name, allow_credit = :allow_credit, first_day_month = :first_day_month, days_to_expiration = :days_to_expiration, active = :active where id = :id");
            $name = $card->getName();
            $allow_credit = $card->getAllowCredit();
            $first_day_month = empty($card->getFirstDayMonth()) ? null : $card->getFirstDayMonth();
            $days_to_expiration = empty($card->getDaysToExpiration()) ? null : $card->getDaysToExpiration();
            $active = $card->getActive();
            $id = $card->getId();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':allow_credit', $allow_credit, PDO::PARAM_BOOL);
            $stmt->bindParam(':first_day_month', $first_day_month, PDO::PARAM_INT);
            $stmt->bindParam(':days_to_expiration', $days_to_expiration, PDO::PARAM_INT);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202007003);

            self::getPDO()->commit();
            return '\'Card\' successfully updated';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202007013);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'card\'. Please inform support', 1202007004, $th);
        }
    }

    public function delete(int $id)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from card where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202007005);

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202007006);

            self::getPDO()->commit();
            return '\'Card\' successfully deleted';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202007007);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while deleting an \'card\'. Please inform support', 1202007010, $th);
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
            if (isset($filters['allow_credit'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " allow_credit = :allow_credit";
            }
            if (isset($filters['active'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " active = :active";
            }

            $sql  = "select * from card $where";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['id'])) {
                $id = $filters['id'];
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            if (isset($filters['name'])) {
                $name = '%' . $filters['name'] . '%';
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            }
            if (isset($filters['allow_credit'])) {
                $allow_credit = $filters['allow_credit'];
                $stmt->bindParam(':allow_credit', $allow_credit, PDO::PARAM_BOOL);
            }
            if (isset($filters['active'])) {
                $active = $filters['active'];
                $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            }

            $cards = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $card = new Card_entity($row->id, $row->wallet_id, $row->name, $row->active, $row->allow_credit, $row->first_day_month, $row->days_to_expiration);
                    $cards[] = $convertJson ? $card->entityToArray() : $card;
                }
            }

            return $cards;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'card\'. Please inform support', 1202007011, $th);
        }
    }
}
    
?>