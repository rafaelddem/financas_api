<?php

namespace financas_api\model\dataAccess;

use Exception;
use financas_api\exceptions\DataNotExistException;
use financas_api\model\entity\Transaction as Transaction_entity;
use \PDO;

class Transaction extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(Transaction_entity $transaction)
    {
        self::getPDO()->beginTransaction();

        try {
            $sql = "insert into transaction (tittle, transaction_date, transaction_type, gross_value, discount_value, relevance, description) ";
            $sql = "values (:tittle, :transaction_date, :transaction_type, :gross_value, :discount_value, :relevance, :description);";
            $stmt = self::getPDO()->prepare($sql);
            
            $tittle = $transaction->getTittle();
            $transaction_date = $transaction->getTransactionDate();
            $transaction_type = $transaction->getTransactionType();
            $gross_value = $transaction->getGrossValue();
            $discount_value = $transaction->getDiscountValue();
            $relevance = $transaction->getRelevance();
            $description = $transaction->getDescription();
            $stmt->bindParam(':tittle', $tittle, PDO::PARAM_STR);
            $stmt->bindParam(':transaction_date', $transaction_date, PDO::PARAM_STR);
            $stmt->bindParam(':transaction_type', $transaction_type, PDO::PARAM_INT);
            $stmt->bindParam(':gross_value', $gross_value, PDO::PARAM_STR);
            $stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
            $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while creating an \'Transaction\'. Please inform support', 1202005001);
            }

            self::getPDO()->commit();
            return '\'Transaction\' successfully created';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while creating an \'Transaction\'. Please inform support', 1202005002);
        }
    }

    // public function update(Transaction_entity $transaction)
    // {
    //     self::getPDO()->beginTransaction();

    //     try {
    //         $stmt = self::getPDO()->prepare("update Transaction set active = :active where id = :id");
    //         $active = $transaction->getActive();
    //         $id = $transaction->getId();
    //         $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
    //         $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    //         if (!$stmt->execute()) {
    //             self::getPDO()->rollback();
    //             throw new Exception('An error occurred while updating an \'Transaction\'. Please inform support', 1202005003);
    //         }

    //         self::getPDO()->commit();
    //         return '\'Transaction\' successfully updated';
    //     } catch (\Throwable $th) {
    //         self::getPDO()->rollback();
    //         throw new Exception('An error occurred while updating an \'Transaction\'. Please inform support', 1202005004);
    //     }
    // }

    // public function delete(int $id)
    // {
    //     self::getPDO()->beginTransaction();

    //     try {
    //         $stmt = self::getPDO()->prepare("delete from Transaction where id = :id");
    //         $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    //         if (!$stmt->execute()) {
    //             self::getPDO()->rollback();
    //             throw new Exception('An error occurred while deleting an \'Transaction\'. Please inform support', 1202005005);
    //         }

    //         if ($stmt->rowCount() <= 0) 
    //             throw new DataNotExistException('There are no data for this \'id\'', 1202005007);

    //         self::getPDO()->commit();
    //         return '\'Transaction\' successfully deleted';
    //     } catch (DataNotExistException $ex) {
    //         throw $ex;
    //     } catch (\Throwable $th) {
    //         self::getPDO()->rollback();
    //         throw new Exception('An error occurred while deleting an \'Transaction\'. Please inform support', 1202005010);
    //     }
    // }

    // public function find(int $id)
    // {
    //     try {
    //         $stmt = self::getPDO()->prepare("select * from Transaction where id = :id");
    //         $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    //         $transaction = '';
    //         if ($stmt->execute()) {
    //             if($stmt->rowCount() > 0) {
    //                 while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    //                     $transaction = new Transaction_entity($row->id, $row->name, boolval($row->active));
    //                 }
    //             } else {
    //                 throw new DataNotExistException('There are no data for this \'id\'', 1202005011);
    //             }
    //         }

    //         return $transaction;
    //     } catch (DataNotExistException $ex) {
    //         throw $ex;
    //     } catch (\Throwable $th) {
    //         throw new Exception('An error occurred while looking for an \'Transaction\'. Please inform support', 1202005012);
    //     }
    // }

    // public function findAll()
    // {
    //     try {
    //         $stmt = self::getPDO()->prepare("select * from Transaction");

    //         $transactions = array();
    //         if ($stmt->execute()) {
    //             while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    //                 $transaction = new Transaction_entity($row->id, $row->name, boolval($row->active));
    //                 $transactions[] = $transaction->entityToJson();
    //             }
    //         }

    //         return $transactions;
    //     } catch (DataNotExistException $ex) {
    //         throw $ex;
    //     } catch (\Throwable $th) {
    //         throw new Exception('An error occurred while looking for an \'Transaction\'. Please inform support', 1202005012);
    //     }
    // }

}
    
?>