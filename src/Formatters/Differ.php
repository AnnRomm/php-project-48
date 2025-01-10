<?php

namespace Formatters\Differ;

use function Functional\sort;

function genDiff(string $firstFile, string $secondFile)
{
    $firstFile = __DIR__.'/../../src/file1.json';
    $secondFile = __DIR__.'/../../src/file2.json';

    $firstFileContent = file_get_contents($firstFile);
    $secondFileContent = file_get_contents($secondFile);

    $data1 = json_decode($firstFileContent, true);
    $data2 = json_decode($secondFileContent, true);

    return compareArrays($data1, $data2);
}

;

function compareArrays(array $data1, array $data2): string
{
    $allKeys = [...array_keys($data1), ...array_keys($data2)];
    $uniqueKeys = array_unique($allKeys);
    $keys = sort($uniqueKeys, fn($left, $right) => strcmp($left, $right));

    $arrayForJson = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        $value1 = array_key_exists($key, $data1) ? normalizeString($data1[$key]) : null;
        $value2 = array_key_exists($key, $data2) ? normalizeString($data2[$key]) : null;

        if (!array_key_exists($key, $data1)) {
            // Ключ только во втором файле
            return [...$acc, "  + {$key}: {$value2}"];
        } elseif (!array_key_exists($key, $data2)) {
            // Ключ только в первом файле
            return [...$acc, "  - {$key}: {$value1}"];
        } elseif ($value1 != $value2) {
            // Значения различаются
            return [...$acc, "  - {$key}: {$value1}\n  + {$key}: {$value2}"];
        } else {
            // Значения одинаковые
            return [...$acc, "    {$key}: {$value1}"];
        }
    }, []);

    $result = ['{', ...$arrayForJson, '}'];
    return implode("\n", $result);
}

function normalizeString($value): string
{
    return is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
}
