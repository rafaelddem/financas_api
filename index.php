<?php

error_reporting(E_ALL ^ E_WARNING);

require_once "vendor/autoload.php";

use financas_api\controller\Owner;
use financas_api\model\entity\URL;
use financas_api\model\entity\Response;

$url = new URL($_SERVER['REQUEST_URI']);

if ($url->getErrors()) {
    Response::send($url->getErrors());
}

$path = $url->getUrlPath();
$parameters = $url->getUrlParameters();

switch ($path) {
    case '':
    case '/':
    case '/home':
        Response::send([
            'software_name' => 'financas_api', 
            'version' => '0.0.1', 
            'developer' => 'Rafael Delfino de Medeiros', 
            'e-mail' => 'rafaelddem@gmail.com', 
        ], true, 200);
        break;

    case '/owner':
        // $owner = new Owner;
        // $owner->create($parameters);
        break;

    case '/owner/create':
        $owner = new Owner;
        $owner->create($parameters);
        break;

    case '/owner/update':
        $owner = new Owner;
        $owner->update($parameters);
        break;

    case '/owner/delete':
        $owner = new Owner;
        $owner->delete($parameters);
        break;

    case '/owner/find':
        $owner = new Owner;
        $owner->find($parameters);
        break;

    default:
        Response::send([
            'error' => 'url not found', 
        ], true, 404);
        break;
}

?>