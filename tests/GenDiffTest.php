<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testStylish()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Stylish-expected.txt");
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.json",
                __DIR__ . "/fixtures/file4.json",
                "stylish"
            )
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.yaml",
                __DIR__ . "/fixtures/file4.yml",
                "stylish"
            )
        );
    }

    public function testPlain()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Plain-expected.txt");
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.json",
                __DIR__ . "/fixtures/file4.json",
                "plain"
            )
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.yaml",
                __DIR__ . "/fixtures/file4.yml",
                "plain"
            )
        );
    }

    public function testJson()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/Json-expected.txt");
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.json",
                __DIR__ . "/fixtures/file4.json",
                "json"
            )
        );
        $this->assertEquals(
            $expected,
            genDiff(
                __DIR__ . "/fixtures/file3.yaml",
                __DIR__ . "/fixtures/file4.yml",
                "json"
            )
        );
    }
}
