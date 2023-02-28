<?php

namespace financas_api\conf;

class Parameters
{
    // Database
    public const CONNECT_DATA_DATABASENAME = "finance_api";
    public const CONNECT_DATA_SERVER = "127.0.0.1";
    public const CONNECT_DATA_USER = "root";
    public const CONNECT_DATA_PASSWORD = "root";

    // Format - Money
    public const DECIMAL_PRECISION = 2;

    // Class - Payment method
    public const PAYMENT_METHOD_TYPE_NOTE = 0;
    public const PAYMENT_METHOD_TYPE_TRANSFER = 1;
    public const PAYMENT_METHOD_TYPE_CARD = 2;

//

    public const FIRST_DAY_OF_MONTH = 5;
}

?>