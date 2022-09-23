<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\InsertInstallmentException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\Installment as Installment_entity;
use financas_api\model\entity\Transaction as Transaction_entity;
use \PDO;
use PDOException;

class Transaction extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(Transaction_entity $transaction) : string
    {
        if (!self::getPDO()->inTransaction()) 
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

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202005001);

            $transaction_id = self::getLastId('transaction');

            $sql  = "insert into installment ";
            $sql .= "(transaction, installment_number, due_date, gross_value, discount_value, interest_value, rounding_value, destination_wallet, source_wallet, payment_method, payment_date) ";
            $sql .= "values ";
            $sql .= "(:transaction, :installment_number, :due_date, :gross_value, :discount_value, :interest_value, :rounding_value, :destination_wallet, :source_wallet, :payment_method, :payment_date);";
            $stmt = self::getPDO()->prepare($sql);
            
            foreach ($transaction->getInstallments() as $installment) {
                $transaction = $transaction_id;
                $installment_number = $installment->getInstallmentNumber();
                $due_date = $installment->getDueDate();
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
                $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
                $stmt->bindParam(':gross_value', $gross_value, PDO::PARAM_STR);
                $stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
                $stmt->bindParam(':interest_value', $interest_value, PDO::PARAM_STR);
                $stmt->bindParam(':rounding_value', $rounding_value, PDO::PARAM_STR);
                $stmt->bindParam(':destination_wallet', $destination_wallet, PDO::PARAM_INT);
                $stmt->bindParam(':source_wallet', $source_wallet, PDO::PARAM_INT);
                $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_INT);
                $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);

                if (!$stmt->execute()) 
                    throw new InsertInstallmentException('An error occurred while creating an \'installment\'. Please inform support', 1202005002);
            }

            self::getPDO()->commit();
            return '\'Transaction\' successfully created';
        } catch (InsertInstallmentException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while creating an \'transaction\'. Please inform support', 1202005003, $th);
        }
    }

    public function update(Transaction_entity $transaction)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $sql  = "update transaction set tittle = :tittle, transaction_type = :transaction_type, relevance = :relevance, description = :description where id = :id;";
            $stmt = self::getPDO()->prepare($sql);
            $tittle = $transaction->getTittle();
            $transaction_type = $transaction->getTransactionType();
            $relevance = $transaction->getRelevance();
            $description = $transaction->getDescription();
            $id = $transaction->getId();
            $stmt->bindParam(':tittle', $tittle, PDO::PARAM_STR);
            $stmt->bindParam(':transaction_type', $transaction_type, PDO::PARAM_INT);
            $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202005004);

            self::getPDO()->commit();
            return '\'Transaction\' successfully updated';
        } catch (PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202005005);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'transaction\'. Please inform support', 1202005006, $th);
        }
    }

    public function delete(int $id)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from transaction where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202005007);

            if ($stmt->rowCount() <= 0) 
                throw new DataNotExistException('There are no data for this \'id\'', 1202005010);

            self::getPDO()->commit();
            return '\'Transaction\' successfully deleted';
        } catch (\PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202001011);
        } catch (DataNotExistException $ex) {
            self::getPDO()->rollback();
            throw $ex;
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while deleting an \'transaction\'. Please inform support', 1202001012, $th);
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
            if (isset($filters['tittle'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " tittle like :tittle";
            }
            if (isset($filters['transaction_date'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " transaction_date = :transaction_date";
            }
            if (isset($filters['transaction_type'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " transaction_type = :transaction_type";
            }
            if (isset($filters['relevance'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " relevance = :relevance";
            }
            if (isset($filters['description'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " description like :description";
            }

            $sql   = "select ";
            $sql  .= self::getTransactionColumns_find(true) . ', ' . self::getInstallmentColumns_find(true);
            $sql  .= " from finance_api.transaction join finance_api.installment on transaction.id = installment.transaction";
            $sql  .= " $where";
            $sql  .= " order by transaction.id";
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['id'])) {
                $id = $filters['id'];
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            if (isset($filters['tittle'])) {
                $tittle = '%' . $filters['tittle'] . '%';
                $stmt->bindParam(':tittle', $tittle, PDO::PARAM_STR);
            }
            if (isset($filters['transaction_date'])) {
                $transaction_date = $filters['transaction_date'];
                $stmt->bindParam(':transaction_date', $transaction_date, PDO::PARAM_STR);
            }
            if (isset($filters['transaction_type'])) {
                $transaction_type = $filters['transaction_type'];
                $stmt->bindParam(':transaction_type', $transaction_type, PDO::PARAM_INT);
            }
            if (isset($filters['relevance'])) {
                $relevance = $filters['relevance'];
                $stmt->bindParam(':relevance', $relevance, PDO::PARAM_INT);
            }
            if (isset($filters['description'])) {
                $description = '%' . $filters['description'] . '%';
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            }

            $installments = array();
            $transactions = array();
            $transaction = array();
            $transaction_id = 0;
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    if ($transaction_id != $row->id AND $transaction_id != 0) {
                        $transaction_entity = new Transaction_entity($transaction['id'], $transaction['tittle'], $transaction['transaction_date'], $transaction['transaction_type'], $transaction['gross_value'], $transaction['discount_value'], $installments, $transaction['relevance'], $transaction['description']);
                        $transactions[] = ($convertJson) ? $transaction_entity->entityToJson() : $transaction_entity;
                        $installments = array();
                    }
                    $transaction_id = $row->id;

                    $transaction['id'] = $row->id;
                    $transaction['tittle'] = $row->tittle;
                    $transaction['transaction_date'] = $row->transaction_date;
                    $transaction['transaction_type'] = $row->transaction_type;
                    $transaction['gross_value'] = $row->transaction_gross_value;
                    $transaction['discount_value'] = $row->transaction_discount_value;
                    $transaction['relevance'] = $row->relevance;
                    $transaction['description'] = $row->description;

                    $installments[] = new Installment_entity($row->transaction, $row->installment_number, $row->installment_due_date, $row->installment_gross_value, $row->installment_discount_value, $row->installment_interest_value, $row->installment_rounding_value, $row->installment_destination_wallet, $row->installment_source_wallet, $row->installment_payment_method, $row->installment_payment_date);
                }

                if ($stmt->rowCount() != 0) {
                    $transaction_entity = new Transaction_entity($transaction['id'], $transaction['tittle'], $transaction['transaction_date'], $transaction['transaction_type'], $transaction['gross_value'], $transaction['discount_value'], $installments, $transaction['relevance'], $transaction['description']);
                    $transactions[] = ($convertJson) ? $transaction_entity->entityToJson() : $transaction_entity;
                }
            }

            return $transactions;
        } catch (DataNotExistException $dnee) {
            throw $dnee;
        } catch (\Throwable $th) {print_r($th);exit;
            throw new UncatalogedException('An error occurred while looking for an \'transaction\'. Please inform support', 1202005013, $th);
        }
    }

    private function getTransactionColumns_find(bool $likeString = false) : array|string
    {
        $columns = [
            'transaction.id', 
            'transaction.tittle', 
            'transaction.transaction_date', 
            'transaction.transaction_type', 
            'transaction.gross_value as transaction_gross_value', 
            'transaction.discount_value as transaction_discount_value', 
            'transaction.relevance', 
            'transaction.description', 
        ];

        return $likeString ? implode(', ', $columns) : $columns;
    }

    private function getInstallmentColumns_find(bool $likeString = false) : array|string
    {
        $columns = [
            'installment.transaction', 
            'installment.installment_number', 
            'installment.due_date as installment_due_date', 
            'installment.gross_value as installment_gross_value', 
            'installment.discount_value as installment_discount_value', 
            'installment.interest_value as installment_interest_value', 
            'installment.rounding_value as installment_rounding_value', 
            'installment.destination_wallet as installment_destination_wallet', 
            'installment.source_wallet as installment_source_wallet', 
            'installment.payment_method as installment_payment_method', 
            'installment.payment_date as installment_payment_date', 
        ];

        return $likeString ? implode(', ', $columns) : $columns;
    }

    private function getLastId(string $table)
    {
        $stmt = self::getPDO()->prepare("select max(id) as id from $table");

        $lastId = 0;
        if ($stmt->execute()) {
            if($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $lastId = $row->id;
                }
            } else {
                throw new UncatalogedException('Could not execute request. Please inform support', 1202005014);
            }
        }

        return $lastId;
    }

}
    
?>