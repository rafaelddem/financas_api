<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{

    /** Data Providers */

    public function dataForNameWithAcceptedSizeAndCharacters()
    {
        return [
            'shortName' => ['Nam'],
            'longName' => ['Big name accept size'],
        ];
    }

    public function dataForNameWithNotAcceptedSize()
    {
        return [
            'veryShortName' => ['Na'],
            'veryLongName' => ['Big name unaccept siz'],
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

    public function dataForFirstDayMonthWithAcceptedValues()
    {
        return [
            [1],
            [2],
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
            [20],
            [21],
            [22],
            [23],
            [24],
            [25],
            [26],
            [27],
            [28],
        ];
    }

    public function dataForFirstDayMonthWithUnacceptedValues()
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
            [0],
            [29],
            [30],
            [31],
            [32],
            [33],
            [34],
            [35],
            [36],
            [37],
            [38],
            [39],
        ];
    }

    /**
     * @dataProvider dataForNameWithAcceptedSizeAndCharacters
     */
    public function testCreateCardWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $card = new Card(0, 1, $name, 1, 10, true);
        self::assertEquals($name, $card->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreateCardWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 20 characters');

        $card = new Card(0, 1, $name, 1, 10, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreateCardWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $card = new Card(0, 1, $name, 1, 10, true);
    }

    /**
     * @dataProvider dataForFirstDayMonthWithAcceptedValues
     */
    public function testCreateCardWithFirstDayMonthWithAcceptedValues(int $firstDayMonth)
    {
        $card = new Card(0, 1, 'Nome do cartao', $firstDayMonth, 1, true);
        self::assertEquals($firstDayMonth, $card->getFirstDayMonth());
    }

    /**
     * @dataProvider dataForFirstDayMonthWithUnacceptedValues
     */
    public function testCreateCardWithFirstDayMonthWithNotAcceptedValues(int $firstDayMonth)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The attribute \'first_day_month\' need to be between 1 and 28');

        $card = new Card(0, 1, 'Nome do cartao', $firstDayMonth, 1, true);
    }
}
?>