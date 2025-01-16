<?php

namespace Differ;

use function Functional\sort;
use function Formatters\Parsers\parse;
use function Differ\Format\chooseFormatter;

function genDiff(string $filePathFirst, string $filePathSecond, string $format = 'stylish'): string
{
    $fileContentFirst = parse($filePathFirst);
    $fileContentSecond = parse($filePathSecond);
    $diff = getDifference($fileContentFirst, $fileContentSecond);
    return chooseFormatter($format, $diff);
}

function getDifference(array $fileContentFirst, array $fileContentSecond): array
{
    return [
        'status' => 'root',
        'value' => getBodyDifference($fileContentFirst, $fileContentSecond)
    ];
}

// Выполняет расчет разницы
function getBodyDifference(array $fileContentFirst, array $fileContentSecond): array
{
    $sortedUnionKeys = getSortedUnionKeys($fileContentFirst, $fileContentSecond);
    return array_map(function ($key) use ($fileContentFirst, $fileContentSecond) {
        if (!array_key_exists($key, $fileContentFirst)) {
            return [
                'status' => 'added',
                'key' => $key,
                'value' => $fileContentSecond[$key]
            ];
        }
        if (!array_key_exists($key, $fileContentSecond)) {
            return [
                'status' => 'deleted',
                'key' => $key,
                'value' => $fileContentFirst[$key]
            ];
        }
        if (is_array($fileContentFirst[$key]) && is_array($fileContentSecond[$key])) {
            return [
                'status' => 'node',
                'key' => $key,
                'value' => getBodyDifference($fileContentFirst[$key], $fileContentSecond[$key])
            ];
        }
        if ($fileContentFirst[$key] === $fileContentSecond[$key]) {
            return [
                'status' => 'unchanged',
                'key' => $key,
                'value' => $fileContentFirst[$key]
            ];
        }
        return [
            'status' => 'changed',
            'key' => $key,
            'valueBefore' => $fileContentFirst[$key],
            'valueAfter' => $fileContentSecond[$key]
        ];
    }, $sortedUnionKeys);
}

// Создает отсортированный массив уникальных ключей двух массивов
function getSortedUnionKeys(array $firstArray, array $secondArray): array
{
    $mergedKeys = array_unique(
        array_merge(
            array_keys($firstArray),
            array_keys($secondArray)
        )
    );
    return sort($mergedKeys, fn ($left, $right) => strcmp($left, $right));
}
