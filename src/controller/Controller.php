<?php

namespace financas_api\controller;

use financas_api\exceptions\RouteNotExistException;

class Controller
{
    private $businessObject;
    private $nameMethod;

    public function __construct($businessObject, $nameMethod)
    {
        if (!(class_exists($businessObject) AND method_exists($businessObject, $nameMethod))) 
            throw new RouteNotExistException('informed route does not exist', 1100003001);

        $this->businessObject = $businessObject;
        $this->nameMethod = $nameMethod;
    }

    public function run(array $parameters = null)
    {
        $method = $this->nameMethod;
        (new $this->businessObject($parameters))->$method();
    }
}

?>