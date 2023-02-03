<?php

namespace financas_api\model\businessObject;

use DateTime;
use api\controller\Response;
use financas_api\model\dataAccess\Reports as Reports_dataAccess;

class Reports
{
    private $owner_id;
    private $start_date;
    private $end_date;

    public function __construct(array $parameters = null)
    {
        $this->owner_id = isset($parameters['owner_id']) ? $parameters['owner_id'] : null;
        $this->start_date = isset($parameters['start_date']) ? $parameters['start_date'] : null;
        $this->end_date = isset($parameters['end_date']) ? $parameters['end_date'] : null;
    }

    public function calculatesTotals()
    {
        try {
            $dao = new Reports_dataAccess();
            // $totals = $dao->calculatesWalletsTotals_byPeriod(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);
            // $totals = $dao->calculatesWalletsTotals_byMonths(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);
            $totals = $dao->calculatesWalletsTotals_byDays(new DateTime($this->start_date), new DateTime($this->end_date), $this->owner_id);

            Response::send(['response' => $totals], true, 200);
        } catch (\Exception $ex) {
            Response::send(['message' => $ex->getMessage(), 'code' => $ex->getCode()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

}
    
?>