<?php

namespace financas_api\exceptions;

use Exception;

class UncatalogedException extends Exception
{
    public function __construct($message, $code, $exception = null) {
        parent::__construct($message, $code, $exception);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>