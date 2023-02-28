<?php

namespace financas_api\model\entity;

use financas_api\conf\Parameters;
use financas_api\exceptions\ValueNotAcceptException;

class PaymentMethod
{
    private int $id;
    private string $name;
    private int $type;
    private bool $active;

    public function __construct(int $id, string $name, int $type, bool $active)
    {
        self::setId($id);
        self::setName($name);
        self::setType($type);
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
        if(strlen($name) < 3 OR strlen($name) > 30)
            throw new ValueNotAcceptException('The \'name\' attribute need to be between 3 and 30 characters', 1201003001);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $name))
            throw new ValueNotAcceptException('The \'name\' attribute only accepts letters and numbers', 1201003002);
        
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setType(int $type)
    {
        if (!in_array($type, array(Parameters::PAYMENT_METHOD_TYPE_NOTE, Parameters::PAYMENT_METHOD_TYPE_TRANSFER, Parameters::PAYMENT_METHOD_TYPE_CARD))) {
            $error_message = "The 'type' attribute was not accepted. You need to use one of the accepted values: '" 
                . Parameters::PAYMENT_METHOD_TYPE_NOTE . "', '" 
                . Parameters::PAYMENT_METHOD_TYPE_TRANSFER . "' or '" 
                . Parameters::PAYMENT_METHOD_TYPE_CARD . "'";
            throw new ValueNotAcceptException($error_message, 1201003003);
        }

        $this->type = $type;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getActive() : bool
    {
        return $this->active;
    }

    public function entityToArray() : array
    {
        return [
            'id' => $this->getId(), 
            'name' => $this->getName(), 
            'type' => $this->getType(), 
            'active' => $this->getActive(), 
        ];
    }

}

?>