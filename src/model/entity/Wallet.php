<?php

namespace financas_api\model\entity;

use financas_api\exceptions\ValueNotAcceptException;

class Wallet
{
    private int $id;
    private string $name;
    private int $owner_id;
    private bool $main_wallet;
    private bool $active;
	private string $description;

    public function __construct(int $id, string $name, int $owner_id, bool $main_wallet, bool $active, string $description = '')
    {
        self::setId($id);
        self::setName($name);
        self::setOwnerId($owner_id);
        self::setMainWallet($main_wallet);
        self::setActive($active);
        self::setDescription($description);
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }

    private function setName(string $name)
    {
        if(strlen($name) < 3 OR strlen($name) > 30)
            throw new ValueNotAcceptException('The \'name\' attribute need to be between 3 and 30 characters', 1201002001);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $name))
            throw new ValueNotAcceptException('The \'name\' attribute only accepts letters and numbers', 1201002002);
        
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    private function setOwnerId(int $owner_id)
    {
        $this->owner_id = $owner_id;
    }

    public function getOwnerId() : int
    {
        return $this->owner_id;
    }

    public function setMainWallet(bool $main_wallet)
    {
        $this->main_wallet = $main_wallet;
    }

    public function getMainWallet() : bool
    {
        return $this->main_wallet;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getActive() : bool
    {
        return $this->active;
    }

    public function setDescription(string $description)
    {
        if(strlen($description) > 255)
            throw new ValueNotAcceptException('The \'description\' attribute must be a maximum of 255 characters', 1201002003);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $description))
            throw new ValueNotAcceptException('The \'description\' attribute only accepts letters and numbers', 1201002004);

        $this->description = $description;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function entityToJson()
    {
        return [
            'id' => $this->getId(), 
            'name' => $this->getName(), 
            'owner_id' => $this->getOwnerId(), 
            'main_wallet' => $this->getMainWallet(), 
            'description' => $this->getDescription(), 
            'active' => $this->getActive(), 
        ];
    }

}
    
?>