<?php

namespace financas_api\model\businessObject;

use DateTime;
use financas_api\controller\Response;
use financas_api\model\dataAccess\Reports as Reports_dataAccess;

class Reports
{
    private $owner_id;

    public function __construct(array $parameters = null)
    {
        $this->owner_id = isset($parameters['owner_id']) ? $parameters['owner_id'] : null;
    }

    public function calculatesTotals()
    {
        try {
            $dao = new Reports_dataAccess();
            $totals = $dao->calculatesTotals(new DateTime('2022-01-01'), new DateTime('2022-05-01'), $this->owner_id);

            Response::send(['response' => $totals], true, 200);
        } catch (\Exception $ex) {
            Response::send(['code' => $ex->getCode(), 'message' => $ex->getMessage()], true, 404);
        } catch (\TypeError $te) {
            Response::send(['message' => 'data provided not accepted, please, see the api manual'], true, 406);
        } catch (\Throwable $th) {
            Response::send(['message' => 'bad request'], true, 400);
        }
    }

}
    
?>