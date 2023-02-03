<?php

namespace financas_api\model\dataAccess;

use \PDO;

class DataAccessObject
{
    private PDO $pdo;

    protected function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->criaPDO();
    }

    protected function getPDO() : PDO
    {
        return $this->pdo;
    }

    // protected function
        
}
    
?>