<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\CardDate;
use PHPUnit\Framework\TestCase;

class CardDateTest extends TestCase
{

    /** Data Providers */

    public function dataForCardDateWithCorrectValues()
    {
        return [
            [
                'allDataCorrectlyInformed' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-06-05', 
                    'value' => 100.00, 
                ],
            ], 
            [
                'endDate1DayDifference' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-05-06', 
                    'value' => 100.00, 
                ],
            ], 
            [
                'endDate10DayDifference' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-05-15', 
                    'value' => 100.00, 
                ],
            ], 
        ];
    }

    public function dataForCardDateWithCorrectValuesWithoutValue()
    {
        return [
            [
                'allDataCorrectlyInformed' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-06-05', 
                ],
            ], 
        ];
    }

    public function dataForValueWithInCorrectValues()
    {
        return [
            [-100.00], 
        ];
    }

    public function dataForEndDateWithInCorrectValues()
    {
        return [
            [
                'allDataCorrectlyInformed' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-05-05', 
                ],
            ], 
            [
                'allDataCorrectlyInformed' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-05-04', 
                ],
            ], 
            [
                'allDataCorrectlyInformed' => [
                    'card_id' => 1, 
                    'start_date' => '2022-05-05', 
                    'end_date' => '2022-04-05', 
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

    /**
     * @dataProvider dataForCardDateWithCorrectValues
     */
    public function testCreateCardDateWithAcceptedValues(array $defaultData)
    {
        $data = $defaultData;
        $cardDate = new CardDate($data['card_id'], $data['start_date'], $data['end_date'], $data['value']);
        self::assertEquals($data['card_id'], $cardDate->getCardId());
        self::assertEquals($data['start_date'], $cardDate->getStartDate());
        self::assertEquals($data['end_date'], $cardDate->getEndDate());
        self::assertEquals($data['value'], $cardDate->getValue());
    }

    /**
     * @dataProvider dataForCardDateWithCorrectValuesWithoutValue
     */
    public function testCreateCardDateWithAcceptedValuesWithoutValue(array $defaultData)
    {
        $data = $defaultData;
        $cardDate = new CardDate($data['card_id'], $data['start_date'], $data['end_date']);
        self::assertEquals($data['card_id'], $cardDate->getCardId());
        self::assertEquals($data['start_date'], $cardDate->getStartDate());
        self::assertEquals($data['end_date'], $cardDate->getEndDate());
    }

    /**
     * @dataProvider dataForValueWithInCorrectValues
     */
    public function testCreateCardDateWithValueWithInCorrectValues(float $value)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The value for \'value\' need to be positive');

        $cardDate = new CardDate(0, '2023-01-01', '2023-01-31', $value);
    }

    /**
     * @dataProvider dataForEndDateWithInCorrectValues
     */
    public function testCreateCardDateWithEndDateWithInCorrectValues(array $defaultData)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'end_date\' need to be greater then \'start_date\'');

        $data = $defaultData;
        $cardDate = new CardDate($data['card_id'], $data['start_date'], $data['end_date']);
        self::assertEquals($data['card_id'], $cardDate->getCardId());
        self::assertEquals($data['start_date'], $cardDate->getStartDate());
        self::assertEquals($data['end_date'], $cardDate->getEndDate());
    }
}
?>