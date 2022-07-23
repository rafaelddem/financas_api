<?php

namespace financas_api\tests;

use Exception;
use financas_api\model\entity\Owner;
use PHPUnit\Framework\TestCase;

class OwnerTest extends TestCase
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

    // public function testCreateOwnerWithCorrectData()
    // {
    //     //o 'id' podem ser negativo
    //     $owner = new Owner(-1, "Nome", true);
    //     self::assertEquals(-1, $owner->getId());

    //     //o 'id' pode ser zero
    //     $owner = new Owner(0, "Nome", true);
    //     self::assertEquals(0, $owner->getId());

    //     //o 'id' pode ser positivo
    //     $owner = new Owner(1, "Nome", true);
    //     self::assertEquals(1, $owner->getId());

    //     //o 'id' pode ser muito grandes
    //     $owner = new Owner((int) INF, "Nome", true);
    //     self::assertEquals((int) INF, $owner->getId());

    //     //o 'active' deve aceitar valores booleanos
    //     $owner = new Owner(0, "Nome", true);
    //     self::assertTrue($owner->getActive());

    //     $owner = new Owner(0, "Nome", false);
    //     self::assertFalse($owner->getActive());
    // }

    /**
     * @dataProvider dataForNameWithAcceptedSizeAndCharacters
     */
    public function testCreateOwnerWithNameWithAcceptedSizeAndCharacters(string $name)
    {
        $owner = new Owner(0, $name, true);
        self::assertEquals($name, $owner->getName());
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedSize
     */
    public function testCreateOwnerWithNameWithNotAcceptedSize(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute need to be between 3 and 30 characters');

        $owner = new Owner(0, $name, true);
    }

    /**
     * @dataProvider dataForNameWithNotAcceptedCharacters
     */
    public function testCreateOwnerWithNameWithNotAcceptedCharacters(string $name)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The \'name\' attribute only accepts letters and numbers');

        $owner = new Owner(0, $name, true);
    }

    // public function testMethodToString()
    // {
    //     $owner = new Owner(10, 'Nome', true);
    //     self::assertEquals('(10) Nome', $owner->__toString());
    // }
}
?>