<?php

namespace financas_api\exceptions\entity;

use Exception;

class DateCreateException extends Exception
{
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>