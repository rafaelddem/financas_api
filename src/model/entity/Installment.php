<?php

namespace financas_api\model\entity;

use DateTime;
use financas_api\conf\Parameters;
use financas_api\exceptions\DateCreateException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;

class Installment
{
    private int $transaction;
    private int $installment_number;
    private DateTime $duo_date;
    private float $gross_value = 0.0;
    private float $discount_value = 0.0;
    private float $interest_value = 0.0;
    private float $rounding_value = 0.0;
    private float $net_value = 0.0;
    private int|null $destination_wallet;
    private int|null $source_wallet;
    private int|null $payment_method;
    private DateTime $payment_date;

    public function __construct(int $transaction, int $installment_number, string $duo_date, 
        float $gross_value, float $discount_value, float $interest_value, float $rounding_value, 
        int $destination_wallet, int $source_wallet = null, int $payment_method = null, string $payment_date = null
    )
    {
        self::setTransaction($transaction);
        self::setInstallmentNumber($installment_number);
        self::setDuoDate($duo_date);
        self::setGrossValue($gross_value);
        self::setDiscountValue($discount_value);
        self::setInterestValue($interest_value);
        self::setRoundingValue($rounding_value);
        
        self::calculateNetValue();
        
        self::setDestinationWallet($destination_wallet);
        self::setSourceWallet($source_wallet);
        self::setPaymentMethod($payment_method);
        self::setPaymentDate($payment_date);
    }

    private function setTransaction(int $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransaction() : int
    {
        return $this->transaction;
    }

    private function setInstallmentNumber(int $installment_number)
    {
        $this->installment_number = $installment_number;
    }

    public function getInstallmentNumber() : int
    {
        return $this->installment_number;
    }

    private function setDuoDate(string $duo_date)
    {
        if ($duo_date == '') 
            throw new EmptyValueException('The value for \'duo_date\' need to be informed', 1201006001);

        try {
            $this->duo_date = new DateTime($duo_date);
            // self::calculateNetValue();
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'duo_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201006002);
        }
    }
    
    public function getDuoDate() : string
    {
        return $this->duo_date->format('Y-m-d');
    }

    private function setGrossValue(float $gross_value)
    {
        if (empty($gross_value) || $gross_value <= 0.0) 
            throw new ValueNotAcceptException('The value for \'gross_value\' need to be positive', 1201006005);

        $this->gross_value = round($gross_value, Parameters::$DECIMAL_PRECISION);
        // self::calculateNetValue();
    }

    public function getGrossValue() : float
    {
        return $this->gross_value;
    }

    public function setDiscountValue(float $discount_value)
    {
        if ($discount_value < 0.0) 
            throw new ValueNotAcceptException('The value for \'discount_value\' need to be positive', 1201006006);

        if ($discount_value >= $this->gross_value) 
            throw new ValueNotAcceptException('The value for \'discount_value\' need to be lower than \'gross_value\'', 1201006007);

        $this->discount_value = round($discount_value, Parameters::$DECIMAL_PRECISION);
        self::calculateNetValue();
    }

    public function getDiscountValue() : float
    {
        return $this->discount_value;
    }

    public function setInterestValue(float $interest_value)
    {
        if ($interest_value < 0.0) 
            throw new ValueNotAcceptException('The value for \'interest_value\' need to be positive', 1201006010);

        $this->interest_value = round($interest_value, Parameters::$DECIMAL_PRECISION);
        self::calculateNetValue();
    }

    public function getInterestValue() : float
    {
        return $this->interest_value;
    }

    public function setRoundingValue(float $rounding_value)
    {
        $this->rounding_value = round($rounding_value, Parameters::$DECIMAL_PRECISION);
        self::calculateNetValue();
    }

    public function getRoundingValue() : float
    {
        return $this->rounding_value;
    }

    public function calculateNetValue()
    {
        $net_value = round(($this->gross_value + $this->interest_value - $this->discount_value) + $this->rounding_value, 2);

        if ($net_value <= 0.0) 
            throw new ValueNotAcceptException('The sum of value need to be positive', 1201006011);

        $this->net_value = $net_value;
    }

    public function getNetValue() : float
    {
        return round($this->net_value, Parameters::$DECIMAL_PRECISION);
    }

    private function setDestinationWallet(int $destination_wallet)
    {
        $this->destination_wallet = $destination_wallet;
    }

    public function getDestinationWallet() : int
    {
        return $this->destination_wallet;
    }

    public function setSourceWallet(int $source_wallet = null)
    {
        // if (empty($payment_date)) 
        //     return;

        $this->source_wallet = $source_wallet;
    }

    public function getSourceWallet() : int|null
    {
        return $this->source_wallet;
    }

    public function setPaymentMethod(int $payment_method = null)
    {
        // if (empty($payment_date)) 
        //     return;

        $this->payment_method = $payment_method;
    }

    public function getPaymentMethod() : int|null
    {
        return $this->payment_method;
    }

    public function setPaymentDate(string $payment_date = null)
    {
        if (empty($payment_date)) 
            return;

        try {
            $this->payment_date = new DateTime($payment_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'payment_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201006003);
        }

        // if ($this->duo_date > $this->payment_date) 
        //     throw new DateCreateException('The value for \'payment_date\' cannot be lower than \'duo_date\'', 1201006004);
    }

    public function getPaymentDate() : string
    {
        if (empty($this->payment_date)) 
            return '';

        return $this->payment_date->format('Y-m-d');
    }

    public function entityToJson()
    {
        $json = [
            'transaction' => $this->getTransaction(), 
            'installment_number' => $this->getInstallmentNumber(), 
            'duo_date' => $this->getDuoDate(), 
            'gross_value' => $this->getGrossValue(), 
            'discount_value' => $this->getDiscountValue(), 
            'interest_value' => $this->getInterestValue(), 
            'rounding_value' => $this->getRoundingValue(), 
            'destination_wallet' => $this->getDestinationWallet(), 
        ];

        if (isset($this->source_wallet)) {
            $json['source_wallet'] = $this->getSourceWallet(); 
        }
        if (isset($this->payment_method)) {
            $json['payment_method'] = $this->getPaymentMethod(); 
        }
        if (isset($this->payment_date)) {
            $json['payment_date'] = $this->getPaymentDate(); 
        }

        return $json;
    }
}

?>