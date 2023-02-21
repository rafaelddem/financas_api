<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\CardDate as CardDate_entity;
use \PDO;

class CardDate extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(CardDate_entity $cardDate)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into card_date (card_id, start_date, end_date, value) values (:card_id, :start_date, :end_date, :value);");
            $card_id = $cardDate->getCardId();
            $start_date = $cardDate->getStartDate();
            $end_date = $cardDate->getEndDate();
            $value = empty($cardDate->getValue()) ? null : $cardDate->getValue();
            $stmt->bindParam(':card_id', $card_id, PDO::PARAM_INT);
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':value', $value, PDO::PARAM_STR);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202008001);

            self::getPDO()->commit();
            return '\'CardDate\' successfully created';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202008012);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'card dates\'. Please inform support', 1202008002, $th);
        }
    }

    public function findByFilter(array $filters, bool $convertJson = true)
    {
        try {
            $where = "";
            if (isset($filters['card_id'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " card_id = :card_id";
            }
            if (isset($filters['start_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " start_date like :start_date";
            }
            if (isset($filters['limit_start_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " start_date >= :limit_start_date";
            }
            if (isset($filters['end_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " end_date like :end_date";
            }
            if (isset($filters['limit_end_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " end_date <= :limit_end_date";
            }
            if (isset($filters['especific_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " :especific_date between start_date and end_date";
            }

            $sql  = "select * from card_date $where";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['card_id'])) {
                $card_id = $filters['card_id'];
                $stmt->bindParam(':card_id', $card_id, PDO::PARAM_INT);
            }
            if (isset($filters['start_date'])) {
                $start_date = $filters['start_date'];
                $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            }
            if (isset($filters['limit_start_date'])) {
                $limit_start_date = $filters['limit_start_date'];
                $stmt->bindParam(':limit_start_date', $limit_start_date, PDO::PARAM_STR);
            }
            if (isset($filters['end_date'])) {
                $end_date = $filters['end_date'];
                $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            }
            if (isset($filters['limit_end_date'])) {
                $limit_end_date = $filters['limit_end_date'];
                $stmt->bindParam(':limit_end_date', $limit_end_date, PDO::PARAM_STR);
            }
            if (isset($filters['especific_date'])) {
                $especific_date = $filters['especific_date'];
                $stmt->bindParam(':especific_date', $especific_date, PDO::PARAM_STR);
            }

            $cardDates = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $cardDate = new CardDate_entity($row->card_id, $row->start_date, $row->end_date);
                    $cardDates[] = $convertJson ? $cardDate->entityToArray() : $cardDate;
                }
            }

            return $cardDates;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'card dates\'. Please inform support', 1202008003, $th);
        }
    }
}
    
?>