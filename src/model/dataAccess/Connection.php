<?php

namespace financas_api\model\dataAccess;

use financas_api\conf\Parameters;
use \PDO;

class Connection
{
    private string $dataBaseName;
    private string $server;
    private string $user;
    private string $password;
        
    public function __construct()
    {
        $this->dataBaseName = Parameters::CONNECT_DATA_DATABASENAME;
        $this->server = Parameters::CONNECT_DATA_SERVER;
        $this->user = Parameters::CONNECT_DATA_USER;
        $this->password = Parameters::CONNECT_DATA_PASSWORD;
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