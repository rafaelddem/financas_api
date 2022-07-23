<?php

namespace financas_api\model\entity;

use Exception;

class URL
{
    private string $url;
    private string $url_path;
    private array $error;
    private array $url_path_array;
    private array $url_parameters_array;

    public function __construct(string $url)
    {
        try {
            $this->url = $url;
    
            $url_array = explode('?', $url);
    
            if (count($url_array) > 2) 
                throw new Exception();
    
            $this->extractPath($url_array[0]);
    
            if (count($url_array) == 2) 
                $this->extractParameter($url_array[1]);
        } catch (\Exception $th) {
            $this->error = array('error' => 'malformed url');
        }
    }

    private function extractPath(string $url_path)
    {
        $this->url_path = $url_path;

        if (in_array($url_path, ['/', ''])) {
            $this->url_path_array = array('/');
            return;
        }

        $url_path_array = explode('/', $url_path);
        array_shift($url_path_array);

        if (in_array('', $url_path_array)) 
            throw new Exception();

        $this->url_path_array = $url_path_array;
    }

    private function extractParameter(string $url_parameters)
    {
        $url_parameters_array = explode('&', $url_parameters);

        foreach ($url_parameters_array as $parameter) {
            if ($parameter == '') 
                continue;

            $parameter_array = explode('=', $parameter);

            if (count($parameter_array) != 2 || $parameter_array[0] == '' || $parameter_array[1] == '') 
                throw new Exception();

            $this->url_parameters_array[$parameter_array[0]] = $parameter_array[1];
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getUrlPath()
    {
        return $this->url_path;
    }

    public function getUrlPathArray()
    {
        return $this->url_path_array;
    }

    public function getUrlParameters()
    {
        return isset($this->url_parameters_array) ? $this->url_parameters_array : array();
    }

    public function getErrors()
    {
        return isset($this->error) ? $this->error : '';
    }
}

?>