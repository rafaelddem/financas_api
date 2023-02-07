<?php

namespace financas_api\model\entity;

use DateTime;
use financas_api\conf\Parameters;
use financas_api\exceptions\DataNotFoundException;
use financas_api\exceptions\DateCreateException;
use financas_api\exceptions\EmptyValueException;
use financas_api\exceptions\ValueNotAcceptException;

class Transaction
{
    private int $id;
    private string $tittle;
    private DateTime $transaction_date;
    private int $transaction_type;
    private float $gross_value = 0.0;
    private float $installments_gross_value = 0.0;
    private float $discount_value = 0.0;
    private float $installments_discount_value = 0.0;
    private float $installments_interest_value = 0.0;
    private float $installments_rounding_value = 0.0;
    private float $net_value = 0.0;
    private array $installments;
    private int $relevance;
    private string $description;

    public function __construct(int $id, string $tittle, string $transaction_date, int $transaction_type, float $gross_value, float $discount_value, array $installments, int $relevance = 0, string $description = '')
    {
        self::setId($id);
        self::setTittle($tittle);
        self::setTransactionDate($transaction_date);
        self::setTransactionType($transaction_type);
        self::setGrossValue($gross_value);
        self::setDiscountValue($discount_value);
        self::setInstallments($installments);

        self::validateValues();

        self::setrelevance($relevance);
        self::setDescription($description);
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setTittle(string $tittle)
    {
        if(strlen($tittle) < 2 OR strlen($tittle) > 50)
            throw new ValueNotAcceptException('The \'tittle\' attribute need to be between 2 and 50 characters', 1201005001);
        else if (preg_match('/[!@#%&*{}?<>:;|\/]/', $tittle))
            throw new ValueNotAcceptException('The \'tittle\' attribute only accepts letters and numbers', 1201005002);
        
        $this->tittle = $tittle;
    }

    public function getTittle() : string
    {
        return $this->tittle;
    }

    private function setTransactionDate(string $transaction_date)
    {
        if ($transaction_date == '') 
            throw new EmptyValueException('The value for \'transaction_date\' need to be informed', 1201005003);

        try {
            $this->transaction_date = new DateTime($transaction_date);
        } catch (\Exception $ex) {
            throw new DateCreateException('The value for \'transaction_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')', 1201005004);
        }
    }

    public function getTransactionDate() : string
    {
        return $this->transaction_date->format('Y-m-d');
    }

    public function setTransactionType(int $transaction_type)
    {
        $this->transaction_type = $transaction_type;
    }

    public function getTransactionType() : int
    {
        return $this->transaction_type;
    }

    private function setGrossValue(float $gross_value)
    {
        if (empty($gross_value) OR $gross_value <= 0.0) 
            throw new ValueNotAcceptException('The value for \'gross_value\' need to be positive', 1201005005);

        $this->gross_value = round($gross_value, Parameters::DECIMAL_PRECISION);
    }

    public function getGrossValue() : float
    {
        return round($this->gross_value, Parameters::DECIMAL_PRECISION);
    }

    private function setDiscountValue(float $discount_value)
    {
        if ($discount_value < 0.0) 
            throw new ValueNotAcceptException('The value for \'discount_value\' need to be positive', 1201005006);

        if ($discount_value >= $this->gross_value) 
            throw new ValueNotAcceptException('The value for \'discount_value\' need to be lower than \'gross_value\'', 1201005007);

        $this->discount_value = round($discount_value, Parameters::DECIMAL_PRECISION);
    }

    public function getDiscountValue() : float
    {
        $discount = $this->discount_value + $this->installments_discount_value;
        return round($discount, Parameters::DECIMAL_PRECISION);
    }

    private function setInstallments(array $installments)
    {
        if (count($installments) < 1) 
            throw new EmptyValueException('The transaction must have payment information', 1201005010);

        $temporary_installments = array();
        foreach ($installments as $installment) {
            if (!($installment instanceof Installment)) 
                throw new ValueNotAcceptException('The installment informed was not accepted', 1201005011);

            $temporary_installments[] = $installment;
        }
        $this->installments = $temporary_installments;
    }

    public function getInstallments() : array
    {
        return $this->installments;
    }

    public function getInstallment(int $installment_id) : Installment
    {
        if (isset($this->installments[$installment_id])) 
            throw new DataNotFoundException('The installment informed, do not exist', 1201005012);

        return $this->installments[$installment_id];
    }

    public function getNumberOfInstallments() : int
    {
        return count($this->installments);
    }

    private function getInstallmentsGrossValue() : float
    {
        return round($this->installments_gross_value, Parameters::DECIMAL_PRECISION);
    }

    public function getInterestValue() : float
    {
        return round($this->installments_interest_value, Parameters::DECIMAL_PRECISION);
    }

    public function getRoundingValue() : float
    {
        return round($this->installments_rounding_value, Parameters::DECIMAL_PRECISION);
    }

    public function getNetValue() : float
    {
        return round($this->net_value, Parameters::DECIMAL_PRECISION);
    }

    private function calculateValues()
    {
        $this->installments_gross_value = 0.0;
        $this->installments_discount_value = 0.0;
        $this->installments_interest_value = 0.0;
        $this->installments_rounding_value = 0.0;
        $this->net_value = 0.0;

        foreach ($this->installments as $installment) {
            $installment->calculateNetValue();
            $this->installments_gross_value += $installment->getGrossValue();
            $this->installments_discount_value += $installment->getDiscountValue();
            $this->installments_interest_value += $installment->getInterestValue();
            $this->installments_rounding_value += $installment->getRoundingValue();
            $this->net_value += $installment->getNetValue();
        }
    }

    public function validateValues()
    {
        self::calculateValues();

        $transaction_value = $this->gross_value - $this->discount_value;
        if ($transaction_value != self::getInstallmentsGrossValue()) 
            throw new ValueNotAcceptException('The sum of the installments\' values don\'t match with the transaction\'s value', 1201005013);

        return true;
    }

    public function setRelevance(int $relevance)
    {
        if (!in_array($relevance, array(0, 1, 2)))
            throw new ValueNotAcceptException('The \'relevance\' attribute was not accepted. You need to use one of the accepted values: \'0\', \'1\' or \'2\'', 1201005014);

        $this->relevance = $relevance;
    }

    public function getRelevance() : int
    {
        return $this->relevance;
    }

    public function setDescription(string $description)
    {
        if(strlen($description) > 255)
            throw new ValueNotAcceptException('The \'description\' attribute must be a maximum of 255 characters', 1201005015);
        else if (preg_match('/[!@#%&*{}?<>:;|\/]/', $description))
            throw new ValueNotAcceptException('The \'description\' attribute only accepts letters and numbers', 1201005016);

        $this->description = $description;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function entityToArray() : array
    {
        $installments_entity = self::getInstallments();
        $installments_json = array();
        foreach ($installments_entity as $installment_entity) {
            $installments_json[] = $installment_entity->entityToArray();
        }

        return [
            'tittle' => $this->getTittle(), 
            'transaction_date' => $this->getTransactionDate(), 
            'transaction_type' => $this->getTransactionType(), 
            'gross_value' => $this->getGrossValue(), 
            'discount_value' => $this->getDiscountValue(), 
            'relevance' => $this->getRelevance(), 
            'description' => $this->getDescription(), 
            'installments' => $installments_json, 
        ];
    }

}

?>