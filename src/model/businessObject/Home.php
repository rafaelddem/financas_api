<?php

namespace financas_api\model\businessObject;

use api\controller\Response;

class Home
{
    public function home()
    {
        Response::send(['response' => 'Home'], true, 200);
    }

    public function backup()
    {
        try {
            include_once "backup_system_data.php";
            include_once "backup.php";

            Response::send(['response' => 'Backup successfully executed'], true, 200);
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