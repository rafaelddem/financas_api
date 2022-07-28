<?php

namespace financas_api\tests;

require_once 'src/conf/parameters.php';

use Exception;
use financas_api\model\entity\Installment;
use PHPUnit\Framework\TestCase;

class InstallmentTest extends TestCase
{
    // default data for correct installment
    private array $defaultData = array();
    private Installment $installment;

    protected function setUp() : void
    {
        $this->defaultData = [
            'transaction' => 1,
            'installment_number' => 1,
            'duo_date' => '2022-05-13',
            'gross_value' => '10.00',
            'discount_value' => '1.0',
            'interest_value' => '0.0',
            'rounding_value' => '0.0',
            'destination_wallet' => 2,
            'source_wallet' => 1,
            'payment_method' => 1,
            'payment_date' => '2022-06-05',
        ];

        $this->installment = new Installment(
            1, //transaction
            1, //installment_number
            '2022-05-13', //duo_date
            10.00, //gross_value
            1.0, //discount_value
            0.0, //interest_value
            0.0, //rounding_value
            2, //destination_wallet
            1, //source_wallet
            1, //payment_method
            '2022-06-05', //payment_date
        );
    }

    /** Data Providers */

    public function dataForInstallmentsWithAcceptedValues()
    {
        return [
            [
                'paymentDateEmpty' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '',
                ],
            ], 
            [
                'duoDateEqualsPaymentDate' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'duoDateLowerThanPaymentDate' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-06-05',
                ],
            ], 
            [
                'grossValueRoundedDown' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.123456',
                    'gross_value_after_rounded' => '10.12',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.12',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-06-05',
                ],
            ], 
            [
                'grossValueRoundedUp' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.987654',
                    'gross_value_after_rounded' => '10.99',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.99',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-06-05',
                ],
            ], 
            [
                'dataWithDiscount' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '1.0',
                    'discount_value_after_rounded' => '1.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '9.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithDiscountRoundedDown' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '1.123456',
                    'discount_value_after_rounded' => '1.12',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '8.88',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithDiscountRoundedUp' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '1.987654',
                    'discount_value_after_rounded' => '1.99',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '8.01',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithInterest' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '1.0',
                    'interest_value_after_rounded' => '1.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '11.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithInterestRoundedDown' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '1.123456',
                    'interest_value_after_rounded' => '1.12',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '11.12',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithInterestRoundedUp' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '1.987654',
                    'interest_value_after_rounded' => '1.99',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '11.99',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'dataWithRoundValue' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.99',
                    'gross_value_after_rounded' => '10.99',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.01',
                    'rounding_value_after_rounded' => '0.01',
                    'net_value_after_rounded' => '11.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            // [
            //     'dataWithRoundValueRoundedDown' => [
            //         'transaction' => 1,
            //         'installment_number' => 1,
            //         'duo_date' => '2022-05-13',
            //         'gross_value' => '10.00',
            //         'gross_value_after_rounded' => '10.00',
            //         'discount_value' => '0.0',
            //         'discount_value_after_rounded' => '0.0',
            //         'interest_value' => '0.0',
            //         'interest_value_after_rounded' => '0.0',
            //         'rounding_value' => '1.123456',
            //         'rounding_value_after_rounded' => '1.12',
            //         'net_value_after_rounded' => '11.12',
            //         'destination_wallet' => 2,
            //         'source_wallet' => 1,
            //         'payment_method' => 1,
            //         'payment_date' => '2022-05-13',
            //     ],
            // ], 
            // [
            //     'dataWithRoundValueRoundedUp' => [
            //         'transaction' => 1,
            //         'installment_number' => 1,
            //         'duo_date' => '2022-05-13',
            //         'gross_value' => '10.00',
            //         'gross_value_after_rounded' => '10.00',
            //         'discount_value' => '0.0',
            //         'discount_value_after_rounded' => '0.0',
            //         'interest_value' => '0.0',
            //         'interest_value_after_rounded' => '0.0',
            //         'rounding_value' => '1.987654',
            //         'rounding_value_after_rounded' => '1.99',
            //         'net_value_after_rounded' => '11.99',
            //         'destination_wallet' => 2,
            //         'source_wallet' => 1,
            //         'payment_method' => 1,
            //         'payment_date' => '2022-05-13',
            //     ],
            // ], 
            [
                'dataWithRoundValue' => [
                    'transaction' => 0,
                    'installment_number' => 0,
                    'duo_date' => '2020-05-01',
                    'gross_value' => '3.99',
                    'gross_value_after_rounded' => '3.99',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.00',
                    'rounding_value_after_rounded' => '0.00',
                    'net_value_after_rounded' => '3.99',
                    'destination_wallet' => 1,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '2020-05-01',
                ],
            ], 
            [
                'sourceWalletEmpty' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 0,
                    'payment_method' => 1,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'paymentMethodEmpty' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 0,
                    'payment_date' => '2022-05-13',
                ],
            ], 
            [
                'paymentDateEmpty' => [
                    'transaction' => 1,
                    'installment_number' => 1,
                    'duo_date' => '2022-05-13',
                    'gross_value' => '10.00',
                    'gross_value_after_rounded' => '10.00',
                    'discount_value' => '0.0',
                    'discount_value_after_rounded' => '0.0',
                    'interest_value' => '0.0',
                    'interest_value_after_rounded' => '0.0',
                    'rounding_value' => '0.0',
                    'rounding_value_after_rounded' => '0.0',
                    'net_value_after_rounded' => '10.0',
                    'destination_wallet' => 2,
                    'source_wallet' => 1,
                    'payment_method' => 1,
                    'payment_date' => '',
                ],
            ], 
        ];
    }

    public function dataForDateInvalid()
    {
        return [
            ['AAAAAA'],
            ['yyyy-mm-dd'],
            ['999999999'],
            ['9898-98'],
            ['9898-98-98'],
            ['9898-9898-98'],
        ];
    }

    /** Tests */

    /**
     * @dataProvider dataForInstallmentsWithAcceptedValues
     */
    public function testCreateInstallmentWithAcceptedValues(array $defaultData)
    {
        $data = $defaultData;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
        self::assertEquals($data['transaction'], $installment->getTransaction());
        self::assertEquals($data['installment_number'], $installment->getInstallmentNumber());
        self::assertEquals($data['duo_date'], $installment->getDuoDate());
        self::assertEquals($data['payment_date'], $installment->getPaymentDate());
        self::assertEquals($data['gross_value_after_rounded'], $installment->getGrossValue());
        self::assertEquals($data['discount_value_after_rounded'], $installment->getDiscountValue());
        self::assertEquals($data['interest_value_after_rounded'], $installment->getInterestValue());
        self::assertEquals($data['rounding_value_after_rounded'], $installment->getRoundingValue());
        self::assertEquals($data['net_value_after_rounded'], $installment->getNetValue());
        self::assertEquals($data['payment_method'], $installment->getPaymentMethod());
        self::assertEquals($data['source_wallet'], $installment->getSourceWallet());
        self::assertEquals($data['destination_wallet'], $installment->getDestinationWallet());
        //testar round
    }

    public function testChangeInstallmentValuesAfterCreate()
    {
        $this->installment->setDiscountValue(1.75);
        self::assertEquals(1.75, $this->installment->getDiscountValue());
        self::assertEquals(8.25, $this->installment->getNetValue());

        $this->installment->setInterestValue(0.65);
        self::assertEquals(0.65, $this->installment->getInterestValue());
        self::assertEquals(8.90, $this->installment->getNetValue());

        $this->installment->setRoundingValue(0.10);
        self::assertEquals(0.10, $this->installment->getRoundingValue());
        self::assertEquals(9.00, $this->installment->getNetValue());
    }

    public function testCreateInstallmentWithDuoDateEmpty()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'duo_date\' need to be informed');

        $data = $this->defaultData;
        $data['duo_date'] = '';
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    /**
     * @dataProvider dataForDateInvalid
     */
    public function testCreateInstallmentWithDuoDateInvalid(string $date)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'duo_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')');

        $data = $this->defaultData;
        $data['duo_date'] = $date;
        $data['payment_date'] = '';
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithPaymentDateEmpty()
    {
        $data = $this->defaultData;
        $data['payment_date'] = $payment_date = '';
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
        self::assertEquals($payment_date, $installment->getPaymentDate());
    }

    /**
     * @dataProvider dataForDateInvalid
     */
    public function testCreateInstallmentWithPaymentDateInvalid(string $date)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'payment_date\' are not accept, confirm value and format (\'yyyy-mm-dd\')');

        $data = $this->defaultData;
        $data['payment_date'] = $date;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithPaymentDateLowerThanDuoDate()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'payment_date\' cannot be lower than \'duo_date\'');

        $data = $this->defaultData;
        $data['payment_date'] = '1000-01-01';
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithGrossValueEmpty()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'gross_value\' need to be positive');

        $data = $this->defaultData;
        $data['gross_value'] = 0.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithGrossValueNegative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'gross_value\' need to be positive');

        $data = $this->defaultData;
        $data['gross_value'] = -10.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithDiscountValueNegative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'discount_value\' need to be positive');

        $data = $this->defaultData;
        $data['discount_value'] = -10.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithDiscountValueEqualGrossValue()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'discount_value\' need to be lower than \'gross_value\'');

        $data = $this->defaultData;
        $data['gross_value'] = 10.0;
        $data['discount_value'] = 10.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithDiscountValueGreaterThanGrossValue()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'discount_value\' need to be lower than \'gross_value\'');

        $data = $this->defaultData;
        $data['gross_value'] = 10.0;
        $data['discount_value'] = 11.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithInterestValueNegative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'interest_value\' need to be positive');

        $data = $this->defaultData;
        $data['interest_value'] = -1.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }

    public function testCreateInstallmentWithTotalValueSumNegative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The sum of value need to be positive');

        $data = $this->defaultData;
        $data['gross_value'] = 10.0;
        $data['discount_value'] = 5.0;
        $data['interest_value'] = 2.0;
        $data['rounding_value'] = -7.0;
        $installment = new Installment($data['transaction'], $data['installment_number'], $data['duo_date'], $data['gross_value'], $data['discount_value'], $data['interest_value'], $data['rounding_value'], $data['destination_wallet'], $data['source_wallet'], $data['payment_method'], $data['payment_date']);
    }
}

?>