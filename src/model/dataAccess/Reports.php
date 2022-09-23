<?php

namespace financas_api\model\dataAccess;

use DateTime;
use financas_api\conf\Parameters;
use financas_api\exceptions\UncatalogedException;
use \PDO;

class Reports extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function calculatesTotals_sql(DateTime $start_date, DateTime $end_date, int $start_day_of_month, int $owner_id, array $group_by = null)
    {
        try {
            $group_by_day = in_array(Parameters::$GROUP_BY_KEY_DAY, $group_by);
            $group_by_month = in_array(Parameters::$GROUP_BY_KEY_MONTH, $group_by);
            $group_by_payment_method = in_array(Parameters::$GROUP_BY_KEY_PAYMENTMETHOD, $group_by);
            $group_by_transaction_type = in_array(Parameters::$GROUP_BY_KEY_TRANSACTIONTYPE, $group_by);

            $select  = "select ";
            $select .= "i.duo_date, ";
            $select .= "CONCAT(DATE_FORMAT(CASE WHEN DATE_FORMAT(i.duo_date, '%d') >= :start_day_of_month_int THEN i.duo_date ELSE DATE_SUB(i.duo_date, INTERVAL 1 MONTH) END, '%Y-%m-'), :start_day_of_month_str) as start_at, ";
            $select .= "w.id as wallet_id, ";
            $select .= "w.name as wallet_name, ";
            $select .= "pm.id as payment_method_id, ";
            $select .= "pm.name as payment_method, ";
            $select .= "tt.id as transaction_type_id, ";
            $select .= "tt.name as transaction_type, ";
            $select .= "sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_in, ";
            $select .= "sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_out, ";
            $select .= "( ";
            $select .= "sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) -  ";
            $select .= "sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) ";
            $select .= ") as values_total ";

            $from  = "from ";
            $from .= "transaction t ";
            $from .= "left join installment i on i.transaction = t.id ";
            $from .= "left join wallet w on w.id = i.source_wallet or w.id = i.destination_wallet ";
            $from .= "left join payment_method pm on pm.id = i.payment_method ";
            $from .= "left join transaction_type tt on tt.id = t.transaction_type ";

            $where  = "where ";
            $where .= "w.owner_id = :owner_id ";
            $where .= "and i.duo_date between :start_date and :end_date ";

            $group  = "group by ";
            if ($group_by_day) $group .= "duo_date, ";
            if ($group_by_month) $group .= "start_at, ";
            $group .= "w.id ";

            $order  = "order by ";
            $order .="duo_date, ";
            $order .= "w.id;";
            
            $sql = "$select $from $where $group $order";
            $stmt = self::getPDO()->prepare($sql);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $start_day_of_month_int = $start_day_of_month;
            $start_day_of_month_str = sprintf("%'.02d", $start_day_of_month);
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
            $stmt->bindParam(':start_day_of_month_int', $start_day_of_month_int, PDO::PARAM_INT);
            $stmt->bindParam(':start_day_of_month_str', $start_day_of_month_str, PDO::PARAM_STR);

            $result = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $total = ['in' => $row->values_in, 'out' => $row->values_out, 'total' => $row->values_total];
                    if ($group_by_day) {
                        $result[$row->duo_date][$row->wallet_id] = $total;
                    } elseif ($group_by_month) {
                        $result[$row->start_at][$row->wallet_id] = $total;
                    } else {
                        $result[$row->wallet_id] = $total;
                    }
                }
            }

            return $result;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }

    public function calculatesWalletsTotals_byPeriod(DateTime $start_date, DateTime $end_date, int $owner_id)
    {
        try {
            $sql  = "CALL finance_api.sum_wallets_by_period(:start_date, :end_date, :owner_id)";
            $stmt = self::getPDO()->prepare($sql);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);

            $result = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $result[$row->wallet_id] = ['in' => $row->values_in, 'out' => $row->values_out, 'total' => $row->values_total];
                }
            }

            return $result;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }

    public function calculatesWalletsTotals_byMonths(DateTime $start_date, DateTime $end_date, int $owner_id)
    {
        try {
            $sql  = "CALL finance_api.sum_wallets_by_months(:start_date, :end_date, :start_day, :owner_id)";
            $stmt = self::getPDO()->prepare($sql);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $start_day = Parameters::$FIRST_DAY_OF_MONTH;
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':start_day', $start_day, PDO::PARAM_INT);
            $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);

            $result = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $result[$row->start_at][$row->wallet_id] = ['in' => $row->values_in, 'out' => $row->values_out, 'total' => $row->values_total];
                }
            }

            return $result;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }

    public function calculatesWalletsTotals_byDays(DateTime $start_date, DateTime $end_date, int $owner_id)
    {
        try {
            $sql  = "CALL finance_api.sum_wallets_by_days(:start_date, :end_date, :owner_id)";
            $stmt = self::getPDO()->prepare($sql);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);

            $result = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $result[$row->duo_date][$row->wallet_id] = ['in' => $row->values_in, 'out' => $row->values_out, 'total' => $row->values_total];
                }
            }

            return $result;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }
}