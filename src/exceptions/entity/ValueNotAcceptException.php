<?php

namespace financas_api\exceptions\entity;

use Exception;

class ValueNotAcceptException extends Exception
{
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>