<?php

namespace financas_api\model\businessObject;

use DateTime;
use financas_api\conf\Parameters;
use financas_api\controller\Response;
use financas_api\model\dataAccess\Reports as Reports_dataAccess;

class Reports
{
    private $owner_id;
    private $start_date;
    private $end_date;
    private $group;
    private $start_at;

    public function __construct(array $parameters = null)
    {
        $this->owner_id = isset($parameters['owner_id']) ? $parameters['owner_id'] : null;
        $this->start_date = isset($parameters['start_date']) ? $parameters['start_date'] : null;
        $this->end_date = isset($parameters['end_date']) ? $parameters['end_date'] : null;
        $this->group = isset($parameters['group']) ? $parameters['group'] : null;
        $this->start_at = isset($parameters['start_at']) ? $parameters['start_at'] : null;
    }

    public function calculatesTotals()
    {
        try {
            $this->start_at = (isset($this->start_at) && $this->start_at > 0) ? $this->start_at : Parameters::$FIRST_DAY_OF_MONTH;

            $dao = new Reports_dataAccess();
            // $totals = $dao->calculatesWalletsTotals_byPeriod(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);
            // $totals = $dao->calculatesWalletsTotals_byMonths(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);
            // $totals = $dao->calculatesWalletsTotals_byDays(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);

            $totals = $dao->calculatesTotals_sql(
                new DateTime($this->start_date), 
                new DateTime($this->end_date), 
                $this->start_at, 
                $this->owner_id, 
                self::setGroup($this->group)
            );

            Response::send(['response' => $totals], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

    private function setGroup(string $group = null)
    {
        if ($group == null) 
            return array();

        $group = explode(';', $group);
        $group = array_map('strtoupper', $group);

        $group_by = array();
        if (in_array(Parameters::$REPORTS_KEY_GROUP_DAY, $group)) {
            $group_by[] = Parameters::$GROUP_BY_KEY_DAY;
        }
        if (in_array(Parameters::$REPORTS_KEY_GROUP_MONTH, $group)) {
            $group_by[] = Parameters::$GROUP_BY_KEY_MONTH;
        }

        if (in_array(Parameters::$REPORTS_KEY_GROUP_OWNER, $group)) {
            $group_by[] = Parameters::$GROUP_BY_KEY_OWNER;
        }
        if (in_array(Parameters::$REPORTS_KEY_GROUP_PAYMENTMETHOD, $group)) {
            $group_by[] = Parameters::$GROUP_BY_KEY_PAYMENTMETHOD;
        }
        if (in_array(Parameters::$REPORTS_KEY_GROUP_TRANSACTIONTYPE, $group)) {
            $group_by[] = Parameters::$GROUP_BY_KEY_TRANSACTIONTYPE;
        }
        return $group_by;
    }

}
    
?>