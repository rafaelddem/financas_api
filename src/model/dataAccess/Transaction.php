<?php

namespace financas_api\model\dataAccess;

use Exception;
use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\InsertInstallmentException;
use financas_api\model\entity\Installment;
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
            $sql  = "insert into transaction (tittle, transaction_date, transaction_type, gross_value, discount_value, relevance, description) ";
            $sql .= "values (:tittle, :transaction_date, :transaction_type, :gross_value, :discount_value, :relevance, :description);";
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
                throw new Exception('An error occurred while creating an \'transaction\'. Please inform support', 1202005001);
            }

            $transaction_id = self::getLastId('transaction');

            $sql  = "insert into installment ";
            $sql .= "(transaction, installment_number, duo_date, gross_value, discount_value, interest_value, rounding_value, destination_wallet, source_wallet, payment_method, payment_date) ";
            $sql .= "values ";
            $sql .= "(:transaction, :installment_number, :duo_date, :gross_value, :discount_value, :interest_value, :rounding_value, :destination_wallet, :source_wallet, :payment_method, :payment_date);";
            $stmt = self::getPDO()->prepare($sql);
            
            foreach ($transaction->getInstallments() as $installment) {
                // $installment = new Installment;
                $transaction = $transaction_id;
                $installment_number = $installment->getInstallmentNumber();
                $duo_date = $installment->getDuoDate();
                $gross_value = $installment->getGrossValue();
                $discount_value = $installment->getDiscountValue();
                $interest_value = $installment->getInterestValue();
                $rounding_value = $installment->getRoundingValue();
                $destination_wallet = $installment->getDestinationWallet();
                $source_wallet = empty($installment->getSourceWallet()) ? null : $installment->getSourceWallet();
                $payment_method = empty($installment->getPaymentMethod()) ? null : $installment->getPaymentMethod();
                $payment_date = empty($installment->getPaymentDate()) ? null : $installment->getPaymentDate();

                $stmt->bindParam(':transaction', $transaction, PDO::PARAM_INT);
                $stmt->bindParam(':installment_number', $installment_number, PDO::PARAM_INT);
                $stmt->bindParam(':duo_date', $duo_date, PDO::PARAM_STR);
                $stmt->bindParam(':gross_value', $gross_value, PDO::PARAM_STR);
                $stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
                $stmt->bindParam(':interest_value', $interest_value, PDO::PARAM_STR);
                $stmt->bindParam(':rounding_value', $rounding_value, PDO::PARAM_STR);
                $stmt->bindParam(':destination_wallet', $destination_wallet, PDO::PARAM_INT);
                $stmt->bindParam(':source_wallet', $source_wallet, PDO::PARAM_INT);
                $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_INT);
                $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    throw new InsertInstallmentException('An error occurred while creating an \'installment\'. Please inform support', 1202005002);
                }
            }

            self::getPDO()->commit();
            return '\'Transaction\' successfully created';
        } catch (InsertInstallmentException $ex) {
            self::getPDO()->rollback();
            throw $ex; //new Exception('An error occurred while creating an \'installment\'. Please inform support', 1202005002);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while creating an \'transaction\'. Please inform support', 1202005003);
        }
    }

    public function getLastId(string $table)
    {
        $stmt = self::getPDO()->prepare("select max(id) as id from $table");

        $lastId = 0;
        if ($stmt->execute()) {
            if($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $lastId = $row->id;
                }
            } else {
                throw new Exception('An error occurred while looking for an \'owner\'. Please inform support', 1202005003);
            }
        }

        return $lastId;
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
    //             throw new Exception('An error occurred while updating an \'transaction\'. Please inform support', 1202005003);
    //         }

    //         self::getPDO()->commit();
    //         return '\'Transaction\' successfully updated';
    //     } catch (\Throwable $th) {
    //         self::getPDO()->rollback();
    //         throw new Exception('An error occurred while updating an \'transaction\'. Please inform support', 1202005004);
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
    //             throw new Exception('An error occurred while deleting an \'transaction\'. Please inform support', 1202005005);
    //         }

    //         if ($stmt->rowCount() <= 0) 
    //             throw new DataNotExistException('There are no data for this \'id\'', 1202005007);

    //         self::getPDO()->commit();
    //         return '\'Transaction\' successfully deleted';
    //     } catch (DataNotExistException $ex) {
    //         throw $ex;
    //     } catch (\Throwable $th) {
    //         self::getPDO()->rollback();
    //         throw new Exception('An error occurred while deleting an \'transaction\'. Please inform support', 1202005010);
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
    //         throw new Exception('An error occurred while looking for an \'transaction\'. Please inform support', 1202005012);
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
    //         throw new Exception('An error occurred while looking for an \'transaction\'. Please inform support', 1202005012);
    //     }
    // }

}
    
?>