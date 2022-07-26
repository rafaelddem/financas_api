<?php

namespace financas_api\model\dataAccess;

use \PDO;

class Conection
{
    private string $dataBaseName;
    private string $server;
    private string $user;
    private string $password;
        
    public function __construct()
    {
        $this->dataBaseName = "finance_api";
        $this->server = "127.0.0.1";
        $this->user = "root";
        $this->password = "root";
    }
        
    public function setDataBaseName(string $dataBaseName)
    {
        $this->dataBaseName = $dataBaseName;
    }
        
    public function getDataBaseName() : string
    {
        return $this->dataBaseName;
    }
        
    public function setServer(string $server)
    {
        $this->server = $server;
    }
        
    public function getServer() : string
    {
        return $this->server;
    }
        
    public function setUser(string $user)
    {
        $this->user = $user;
    }
        
    public function getUser() : string
    {
        return $this->user;
    }
        
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
        
    public function getPassword() : string
    {
        return $this->password;
    }
        
    public function criaPDO()
    {
        return new PDO("mysql:host=" . $this->server . ";dbname=" . $this->dataBaseName, $this->user, $this->password);
    }
        
}

?>