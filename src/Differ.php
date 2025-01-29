<?php

namespace Differ\Differ;

use function Differ\Formatters\chooseFormatter;
use function Formatters\Parsers\parse;
use function Functional\sort;

function genDiff(string $filePathFirst, string $filePathSecond, string $format = 'stylish'): string
{
    $fileDataFirst = getFileData($filePathFirst);
    $fileDataSecond = getFileData($filePathSecond);

    $fileContentFirst = parse($fileDataFirst['content'], $fileDataFirst['extension']);
    $fileContentSecond = parse($fileDataSecond['content'], $fileDataSecond['extension']);

    $diff = getDifference($fileContentFirst, $fileContentSecond);
    return chooseFormatter($format, $diff);
}

function getFileData(string $filePath): array
{
    if (!file_exists($filePath) || !is_readable($filePath)) {
        throw new \RuntimeException("Error reading file: " . basename($filePath));
    }

    $extension = pathinfo($filePath)['extension'] ?? null;
    $content = file_get_contents($filePath);

    if ($content === false) {
        throw new \RuntimeException("Failed to read file: " . basename($filePath));
    }
    return ['content' => $content, 'extension' => $extension];
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
    return sort($mergedKeys, fn($left, $right) => strcmp($left, $right));
}
