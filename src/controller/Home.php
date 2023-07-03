<?php

namespace financas_api\controller;

use financas_api\model\businessObject\Home as BusinessObjectHome;

class Home
{
    public function home()
    {
        (new BusinessObjectHome)->home();
    }

    public function backup()
    {
        (new BusinessObjectHome)->backup();
    }

}

?>