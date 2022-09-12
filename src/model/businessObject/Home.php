<?php

namespace financas_api\model\businessObject;

use financas_api\controller\Response;

class Home
{
    public function home()
    {
        Response::send(['response' => 'Home'], true, 200);
    }

    public function backup()
    {
        try {
            include_once "backup.php";

            Response::send(['response' => 'Backup successfully executed'], true, 200);
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