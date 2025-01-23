<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private string $path = __DIR__ . "/fixtures/";

    private function getFilePath($name): string
    {
        return $this->path . $name;
    }

    /**
     * @dataProvider runDifferProvider
     */
    public function testFileComparison($file1, $file2, $format, $expected): void
    {
        $this->assertStringEqualsFile(
            $this->getFilePath($expected),
            genDiff($this->getFilePath($file1), $this->getFilePath($file2), $format)
        );
    }

    public function runDifferProvider(): array
    {
        return [

            'JSON format: JSON vs JSON' => ['file1.json', 'file2.json', 'json', 'Json-expected.txt'],
            'JSON format: YAML vs YML' => ['file1.yaml', 'file2.yml', 'json', 'Json-expected.txt'],
            'JSON format: JSON vs YML' => ['file1.json', 'file2.yml', 'json', 'Json-expected.txt'],

            'Stylish format: JSON vs JSON' => ['file1.json', 'file2.json', 'stylish', 'Stylish-expected.txt'],
            'Stylish format: YAML vs YML' => ['file1.yaml', 'file2.yml', 'stylish', 'Stylish-expected.txt'],
            'Stylish format: JSON vs YML' => ['file1.json', 'file2.yml', 'stylish', 'Stylish-expected.txt'],

            'Plain format: JSON vs JSON' => ['file1.json', 'file2.json', 'plain', 'Plain-expected.txt'],
            'Plain format: YAML vs YML' => ['file1.yaml', 'file2.yml', 'plain', 'Plain-expected.txt'],
            'Plain format: JSON vs YML' => ['file1.json', 'file2.yml', 'plain', 'Plain-expected.txt']
        ];
    }
}
