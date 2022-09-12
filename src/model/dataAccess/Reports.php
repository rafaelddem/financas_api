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

    public function calculatesTotals(DateTime $start_date, DateTime $end_date, int $owner)
    {
        try {
            $sql  = "CALL finance_api.sum_wallets(:start_date, :end_date, :start_day, :owner)";
            $stmt = self::getPDO()->prepare($sql);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $start_day = Parameters::$FIRST_DAY_OF_MONTH;
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':start_day', $start_day, PDO::PARAM_INT);
            $stmt->bindParam(':owner', $owner, PDO::PARAM_INT);

            $result = array();
            if ($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $result[$row->start_at][$row->wallet_id] = $row->values_total;
                }
            }

            return $result;
        } catch (\Throwable $th) {
            throw new UncatalogedException('An error occurred while looking for an \'owner\'. Please inform support', 1202002011);
        }
    }
}