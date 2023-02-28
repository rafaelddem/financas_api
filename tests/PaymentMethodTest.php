<?php

namespace financas_api\tests;

use Exception;
use financas_api\conf\Parameters;
use financas_api\model\entity\PaymentMethod;
use PHPUnit\Framework\TestCase;

class PaymentMethodTest extends TestCase
{

    /** Data Providers */

    public function dataForNameWithAcceptedSizeAndCharacters()
    {
        return [
            'shortName' => ['Nam'],
            'longName' => ['Big name but with accept size.'],
        ];
    }

    public function dataForNameWithNotAcceptedSize()
    {
        return [
            'veryShortName' => ['Na'],
            'veryLongName' => ['A big name with unaccept value.'],
        ];
    }

    public function dataForNameWithNotAcceptedCharacters()
    {
        return [
            ['Name with /'],
            ['Name with !'],
            ['Name with @'],
            ['Name with #'],
            ['Name with $'],
            ['Name with %'],
            ['Name with &'],
            ['Name with *'],
            ['Name with {'],
            ['Name with }'],
            ['Name with $'],
            ['Name with ?'],
            ['Name with <'],
            ['Name with >'],
            ['Name with :'],
            ['Name with ;'],
            ['Name with |'],
            ['Name with /'],
            // ['Name with \\'],
            // ['Name with ['],
            // ['Name with ]'],
        ];
    }

    public function dataForTypeWithNotAcceptedCharacters()
    {
        return [
            [-10],
            [-9],
            [-8],
            [-7],
            [-6],
            [-5],
            [-4],
            [-3],
            [-2],
            [-1],
            // [0],
            // [1],
            // [2],
            [3],
            [4],
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
            [11],
            [12],
            [13],
            [14],
            [15],
            [16],
            [17],
            [18],
            [19],
        ];
    }

    /** Tests */

    /**
     * @dataProvider dataForNameWithAcceptedSizeAndCharacters
     */
    public function testCreatePaymentMethodWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $payment_method = new PaymentMethod(0, $name, 0, true);
        self::assertEquals($name, $payment_method->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreatePaymentMethodWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 30 characters');

        $payment_method = new PaymentMethod(0, $name, 0, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreatePaymentMethodWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $payment_method = new PaymentMethod(0, $name, 0, true);
    }

    /**
     * @dataProvider dataForTypeWithNotAcceptedCharacters
     */
    public function testCreatePaymentMethodWithTypeWithNotAcceptedCharacters(int $type)
    {
        $error_message = "The 'type' attribute was not accepted. You need to use one of the accepted values: '" 
            . Parameters::PAYMENT_METHOD_TYPE_NOTE . "', '" 
            . Parameters::PAYMENT_METHOD_TYPE_TRANSFER . "' or '" 
            . Parameters::PAYMENT_METHOD_TYPE_CARD . "'";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error_message);

        $payment_method = new PaymentMethod(0, 'Name', $type, true);
    }
}
?>