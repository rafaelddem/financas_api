<?php

namespace financas_api\controller;

use financas_api\controller\Controller;
use financas_api\exceptions\RouteNotExistException;

class Route
{
    static array $routes = array();

    static function addGet(string $path, Controller $controller)
    {
        self::$routes['GET'][$path] = $controller;
    }

    static function addPost(string $path, Controller $controller)
    {
        self::$routes['POST'][$path] = $controller;
    }

    static function addPut(string $path, Controller $controller)
    {
        self::$routes['PUT'][$path] = $controller;
    }

    static function addDelete(string $path, Controller $controller)
    {
        self::$routes['DELETE'][$path] = $controller;
    }

    static function getPath()
    {
        $baseMethod = new GET;
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $baseMethod = new POST;
                break;

            case 'PUT':
                $baseMethod = new PUT;
                break;

            case 'DELETE':
                $baseMethod = new DELETE;
                break;

            default:
                $baseMethod = new GET;
                break;
        }

        if (!isset(self::$routes[$baseMethod->getMethodType()][$baseMethod->getUrlPath()])) 
            throw new RouteNotExistException('informed route does not exist', 1100002001);

        self::$routes[$baseMethod->getMethodType()][$baseMethod->getUrlPath()]->run($baseMethod->getUrlParameters());
    }
}

?>