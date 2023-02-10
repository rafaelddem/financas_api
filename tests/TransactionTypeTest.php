<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\TransactionType;
use PHPUnit\Framework\TestCase;

class TransactionTypeTest extends TestCase
{

    /** Data Providers */

    public function dataForNameWithAcceptedSizeAndCharacters()
    {
        return [
            'shortName' => ['Nam'],
            'longName' => ['A large name for tests, but with accept size.'],
        ];
    }

    public function dataForNameWithNotAcceptedSize()
    {
        return [
            'veryShortName' => ['Na'],
            'veryLongName' => ['A very large name for tests, with accept size.'],
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

    public function dataForRelevanceWithAcceptedValues()
    {
        return [
            [0],
            [1],
            [2],
        ];
    }

    public function dataForRelevanceWithNotAcceptedValues()
    {
        return [
            // [(int) -INF],
            [-10],
            [-3],
            [-2],
            [-1],
            [3],
            [10],
            // [(int) INF],
        ];
    }

    /** Tests */

    /**
     * @dataProvider dataForNameWithAcceptedSizeAndCharacters
     */
    public function testCreateTransactionTypeWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $transaction_type = new TransactionType(0, $name, 0, true);
        self::assertEquals($name, $transaction_type->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreateTransactionTypeWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 45 characters');

        $transaction_type = new TransactionType(0, $name, 0, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreateTransactionTypeWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $transaction_type = new TransactionType(0, $name, 0, true);
    }

    /**
     * @dataProvider dataForRelevanceWithAcceptedValues
     */
    public function testCreateTransactionTypeWithRelevanceWithAcceptedValues(int $relevance)
    {
        $transaction_type = new TransactionType(0, 'Name', $relevance, true);
        self::assertEquals($relevance, $transaction_type->getRelevance());
    }

    /**
     * @dataProvider dataForRelevanceWithNotAcceptedValues
     */
    public function testCreateTransactionTypeWithRelevanceWithNotAcceptedValues(int $relevance)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'relevance\' attribute was not accepted. You need to use one of the accepted values: \'0\', \'1\' or \'2\'');

        $transaction_type = new TransactionType(0, 'Name', $relevance, true);
    }
}
?>