<?php

namespace financas_api\controller;

use Exception;

class GET extends BaseMethod
{
    public function __construct()
    {
        parent::__construct();
        $this->method_type = 'GET';
    }

    protected function extractParameter()
    {
        $url_parameters = explode('?', $this->url);

        if (count($url_parameters) > 2) 
            throw new Exception();

        if (count($url_parameters) < 2) 
            return;

        $url_parameters_array = explode('&', $url_parameters[1]);

        foreach ($url_parameters_array as $parameter) {
            if ($parameter == '') 
                continue;

            $parameter_array = explode('=', $parameter);

            if (count($parameter_array) != 2 OR $parameter_array[0] == '' OR $parameter_array[1] == '') 
                throw new Exception();

            $this->url_parameters_array[$parameter_array[0]] = $parameter_array[1];
        }
    }
}

?>