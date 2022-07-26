<?php

namespace financas_api\model\entity;

use financas_api\exceptions\ValueNotAcceptException;

class PaymentMethod
{
    private int $id;
    private string $name;
    private bool $active;

    public function __construct(int $id, string $name, bool $active)
    {
        self::setId($id);
        self::setName($name);
        self::setActive($active);
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
        if(strlen($name) < 3 or strlen($name) > 30)
            throw new ValueNotAcceptException('The \'name\' attribute need to be between 3 and 30 characters', 1201003001);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $name))
            throw new ValueNotAcceptException('The \'name\' attribute only accepts letters and numbers', 1201003002);
        
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getActive() : bool
    {
        return $this->active;
    }

    public function entityToJson()
    {
        return [
            'id' => $this->getId(), 
            'name' => $this->getName(), 
            'active' => $this->getActive(), 
        ];
    }

}

?>