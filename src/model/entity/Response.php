<?php

namespace financas_api\model\entity;

class Response
{
    static function send(array $responseData, bool $replace = true, int $htmlCode = 200)
    {
        header('Content-type: application/json', $replace, $htmlCode);
        echo json_encode($responseData);
    }
}

?>