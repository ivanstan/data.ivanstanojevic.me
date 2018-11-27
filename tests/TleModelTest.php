<?php

namespace App\Tests;

use App\Model\TleModel;
use PHPUnit\Framework\TestCase;

class TleModelTest extends TestCase
{
    public function testParse(): void
    {
        $name = '1998-067NY';
        $line1 = '1 43550U 98067NY  18321.21573649  .00013513  00000-0  18402-3 0  9990';
        $line2 = '2 43550  51.6389 334.0891 0005785  67.0956 293.0647 15.57860024 19804';

        $tle = new TleModel($line1, $line2, $name);

        static::assertEquals(
            '2018-11-17T05:10:39+00:00',
            $tle->getDate(),
            'Failed asserting TLE returned correct date'
        );

        static::assertEquals(
            0,
            $tle->getChecksum(TleModel::LINE1),
            'Failed asserting TLE checksum for line1 is correct'
        );

        static::assertEquals(
            4,
            $tle->getChecksum(TleModel::LINE2),
            'Failed asserting TLE checksum for line2 is correct'
        );

        static::assertEquals(
            0,
            $tle->calculateChecksum(TleModel::LINE1),
            'Failed asserting TLE calculated checksum for line1 is correct'
        );

        static::assertEquals(
            4,
            $tle->calculateChecksum(TleModel::LINE2),
            'Failed asserting TLE calculated checksum for line2 is correct'
        );

        static::assertEquals(
            true,
            $tle->verify(),
            'Failed asserting that TLE is correct'
        );

        static::assertEquals(
            43550,
            $tle->getId(),
            'Failed asserting that TLE Satellite/Catalog number is correct'
        );

        static::assertEquals(
            'U',
            $tle->getClassification(),
            'Failed asserting that TLE classification is correct'
        );
    }
}
