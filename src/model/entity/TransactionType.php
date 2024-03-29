<?php

namespace financas_api\model\entity;

use financas_api\exceptions\ValueNotAcceptException;

class TransactionType
{
    private int $id;
    private string $name;
    private int $relevance;
    private bool $active;

    public function __construct(int $id, string $name, int $relevance, bool $active)
    {
        self::setId($id);
        self::setName($name);
        self::setRelevance($relevance);
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
        if(strlen($name) < 3 OR strlen($name) > 45)
            throw new ValueNotAcceptException('The \'name\' attribute need to be between 3 and 45 characters', 1201004001);
        else if (preg_match('/[!@#$%&*{}$?<>:;|\/]/', $name))
            throw new ValueNotAcceptException('The \'name\' attribute only accepts letters and numbers', 1201004002);
        
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setRelevance(int $relevance)
    {
        if (!in_array($relevance, array(0, 1, 2)))
            throw new ValueNotAcceptException('The \'relevance\' attribute was not accepted. You need to use one of the accepted values: \'0\', \'1\' or \'2\'', 1201004003);

        $this->relevance = $relevance;
    }

    public function getRelevance() : int
    {
        return $this->relevance;
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
            'relevance' => $this->getRelevance(), 
            'active' => $this->getActive(), 
        ];
    }
    
}

?>