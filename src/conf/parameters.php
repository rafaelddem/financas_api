<?php

namespace financas_api\conf;

class Parameters
{
    /** About formatation **/
    public const DECIMAL_PRECISION = 2;

    /** About user's particularity **/
    public const FIRST_DAY_OF_MONTH = 5;

    /** About code organization **/
    public const EXCEPTIONCODE_LEVEL_CONTROLLER = 11;
    public const EXCEPTIONCODE_LEVEL_MODEL = 12;
    public const EXCEPTIONCODE_SUBLEVEL_BUSINESSOBJECT = 1;
    public const EXCEPTIONCODE_SUBLEVEL_DATAACCESS = 2;
    public const EXCEPTIONCODE_SUBLEVEL_ENTITY = 3;
    public const EXCEPTIONCODE_CLASS_HOME = 0;
    public const EXCEPTIONCODE_CLASS_INSTALLMENT = 1;
    public const EXCEPTIONCODE_CLASS_OWNER = 2;
    public const EXCEPTIONCODE_CLASS_PAYMENTMETHOD = 3;
    public const EXCEPTIONCODE_CLASS_REPORTS = 4;
    public const EXCEPTIONCODE_CLASS_TRANSACTION = 5;
    public const EXCEPTIONCODE_CLASS_TRANSACTIONTYPE = 6;
    public const EXCEPTIONCODE_CLASS_WALLET = 7;
}

?>