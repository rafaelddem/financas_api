<?php

namespace financas_api\model\dataAccess;

use \PDO;

class DataAccessObject
{
    private PDO $pdo;

    protected function __construct()
    {
        $conexao = new Conection();
        $this->pdo = $conexao->criaPDO();
    }

    protected function getPDO() : PDO
    {
        return $this->pdo;
    }

    // protected function
        
}
    
?>