<?php

namespace financas_api\model\dataAccess;

use financas_api\exceptions\DataNotExistException;
use financas_api\exceptions\IntegrityException;
use financas_api\exceptions\UncatalogedException;
use financas_api\model\entity\Installment as Installment_entity;
use \PDO;
use PDOException;

class Installment extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update(Installment_entity $installment)
    {
        if (!self::getPDO()->inTransaction()) 
            self::getPDO()->beginTransaction();

        try {
            $sql  = "update installment set ";
            $sql .= "discount_value = :discount_value, interest_value = :interest_value, rounding_value = :rounding_value, ";
            $sql .= "source_wallet = :source_wallet, payment_method = :payment_method, payment_date = :payment_date ";
            $sql .= "where transaction = :transaction and installment_number = :installment_number;";
            $stmt = self::getPDO()->prepare($sql);
            $discount_value = $installment->getDiscountValue();
            $interest_value = $installment->getInterestValue();
            $rounding_value = $installment->getRoundingValue();
            $source_wallet = $installment->getSourceWallet();
            $payment_method = $installment->getPaymentMethod();
            $payment_date = $installment->getPaymentDate();
            $transaction = $installment->getTransaction();
            $installment_number = $installment->getInstallmentNumber();
            $stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
            $stmt->bindParam(':interest_value', $interest_value, PDO::PARAM_STR);
            $stmt->bindParam(':rounding_value', $rounding_value, PDO::PARAM_STR);
            $stmt->bindParam(':source_wallet', $source_wallet, PDO::PARAM_INT);
            $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_INT);
            $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);
            $stmt->bindParam(':transaction', $transaction, PDO::PARAM_INT);
            $stmt->bindParam(':installment_number', $installment_number, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                throw new UncatalogedException('Could not execute request. Please inform support', 1202006001);

            self::getPDO()->commit();
            return '\'Installment\' successfully updated';
        } catch (PDOException $pdoe) {
            self::getPDO()->rollback();
            throw new IntegrityException($pdoe, 1202006002);
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new UncatalogedException('An error occurred while updating an \'installment\'. Please inform support', 1202006003, $th);
        }
    }

    public function findByFilter(array $filters, bool $convertJson = true)
    {
        try {
            $where = "";
            if (isset($filters['transaction'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " transaction = :transaction";
            }
            if (isset($filters['installment_number'])) {
                $where .= $where == "" ? " where" : " and";
                $where .= " installment_number = :installment_number";
            }

            $sql   = 'select ' . self::getInstallmentColumns_find(true) . ' from finance_api.installment';
            $sql  .= " $where";
            $sql  .= ' order by installment.transaction and installment.installment_number';
            $stmt = self::getPDO()->prepare($sql);

            if (isset($filters['transaction'])) {
                $transaction = $filters['transaction'];
                $stmt->bindParam(':transaction', $transaction, PDO::PARAM_INT);
            }
            if (isset($filters['installment_number'])) {
                $installment_number = $filters['installment_number'];
                $stmt->bindParam(':installment_number', $installment_number, PDO::PARAM_INT);
            }

            $installments = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $installment = new Installment_entity($row->transaction, $row->installment_number, $row->installment_due_date, $row->installment_gross_value, $row->installment_discount_value, $row->installment_interest_value, $row->installment_rounding_value, $row->installment_destination_wallet, $row->installment_source_wallet, $row->installment_payment_method, $row->installment_payment_date);
                    $installments[] = ($convertJson) ? $installment->entityToArray() : $installment;
                }
            }

            return $installments;
        } catch (DataNotExistException $dnee) {
            throw $dnee;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'transaction\'. Please inform support', 1202006004, $th);
        }
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

}
    
?>