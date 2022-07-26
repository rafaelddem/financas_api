<?php

namespace financas_api\controller;

use financas_api\exceptions\InvalidURLException;

abstract class BaseMethod
{
    protected string $method_type;
    protected string $url;
    protected string $url_path;
    protected string $content_type;
    protected array $url_parameters_array;

    public function __construct()
    {
        try {
            $this->url = $_SERVER['REQUEST_URI'];

            $this->extractPath();
            $this->extractContentType();
            $this->extractParameter();
        } catch (\Throwable $th) {
            throw new InvalidURLException('malformed url', 1100001001);
        }
    }

    public function extractPath()
    {
        $url_path = explode('?', $this->url);
        $this->url_path = $url_path[0];
    }

    public function extractContentType()
    {
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $this->content_type = 'json';
        } else {
            $this->content_type = 'form';
        }
    }

    abstract protected function extractParameter();

    public function getMethodType()
    {
        return $this->method_type;
    }

    public function getUrlPath()
    {
        return $this->url_path;
    }

    public function getUrlParameters()
    {
        return isset($this->url_parameters_array) ? $this->url_parameters_array : array();
    }
}

?>