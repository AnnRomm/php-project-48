<?php

namespace Phpunit\Tests;

use function Formatters\Differ\genDiff;

use PHPUnit\Framework\TestCase;

class GenDiffTest  extends TestCase
{

    public function testDiffJson()
    {
        $expected = file_get_contents(__DIR__."/fixtures/result.txt");
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__."/fixtures/file1.json",
                __DIR__."/fixtures/file2.json"
            )
        );
    }
}
