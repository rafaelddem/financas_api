<?php

namespace financas_api\controller;

class POST extends BaseMethod
{
    public function __construct()
    {
        parent::__construct();
        $this->method_type = 'POST';
    }

    protected function extractParameter()
    {
        switch ($this->content_type) {
            case 'json':
                $this->url_parameters_array = json_decode(file_get_contents('php://input'), true);
                break;

            case 'form':
                $this->url_parameters_array = $_POST;
                break;
        }
    }
}

?>