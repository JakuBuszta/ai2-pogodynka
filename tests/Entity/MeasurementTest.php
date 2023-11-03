<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['11', 51.8],
            ['12', 53.6],
            ['20', 68],
            ['75', 167],
            ['40', 104],
            ['23', 73.4],
            ['0.5', 32.9],
        ];
    }


    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void {
        $measurement = new Measurement();

        $measurement->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrehneit());
    }
}
