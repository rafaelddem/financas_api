<?php

namespace financas_api\exceptions;

use PDOException;

class IntegrityException extends PDOException
{
    public function __construct(PDOException $exception, $customCode) {
        $pdoException = $exception->errorInfo;
        $pdoCode = $pdoException[0];
        $pdoSpecificCode = $pdoException[1];
        $pdoMessage = $pdoException[2];

        switch ($pdoSpecificCode) {
            case 1062:
                $message = $exception->errorInfo[2];
                break;
            case 1451:
                $message = 'Exclusion not allowed. Other data is linked to this data';
                break;

            default:
                $message = "Uncataloged error. Please inform support (code: $pdoCode:$pdoSpecificCode)";
                break;
        }

        parent::__construct($message, $customCode);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>