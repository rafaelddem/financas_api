<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
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
    public function testCreateWalletWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $wallet = new Wallet(0, $name, 1, true, true);
        self::assertEquals($name, $wallet->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreateWalletWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 30 characters');

        $wallet = new Wallet(0, $name, 1, true, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreateWalletWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $wallet = new Wallet(0, $name, 1, true, true);
    }

}
?>