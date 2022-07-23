<?php

namespace financas_api\tests;

use Exception;
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

    /** Tests */

    /**
     * @dataProvider dataForNameWithAcceptedSizeAndCharacters
     */
    public function testCreatePaymentMethodWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $payment_method = new PaymentMethod(0, $name, true);
        self::assertEquals($name, $payment_method->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreatePaymentMethodWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 30 characters');

        $payment_method = new PaymentMethod(0, $name, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreatePaymentMethodWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $payment_method = new PaymentMethod(0, $name, true);
    }
}
?>