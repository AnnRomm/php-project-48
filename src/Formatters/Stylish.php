<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $difference): string
{
    return formatDiff($difference) . "\n";
}

function formatDiff(array $difference, int $depth = 0): string
{
    $spaces = makeSpace($depth);
    $nextDepth = $depth + 1;
    switch ($difference['status']) {
        case 'root':
            $lines = array_map(function ($node) use ($depth) {
                return formatDiff($node, $depth);
            }, $difference['value']);
            $output = ['{', ...$lines, '}'];
            return implode("\n", $output);
        case 'node':
            $lines = array_map(function ($node) use ($nextDepth) {
                return formatDiff($node, $nextDepth);
            }, $difference['value']);
            $output = ["$spaces    {$difference['key']}: {", ...$lines, "$spaces    }"];
            return implode("\n", $output);
        case 'added':
            $stringValue = getStringValue($difference['value'], $nextDepth);
            return sprintf("%s  + %s: %s", $spaces, $difference['key'], $stringValue);
        case 'deleted':
            $stringValue = getStringValue($difference['value'], $nextDepth);
            return sprintf("%s  - %s: %s", $spaces, $difference['key'], $stringValue);
        case 'unchanged':
            $stringValue = getStringValue($difference['value'], $nextDepth);
            return sprintf("%s    %s: %s", $spaces, $difference['key'], $stringValue);
        case 'changed':
            $stringValueBefore = getStringValue($difference['valueBefore'], $nextDepth);
            $stringValueAfter = getStringValue($difference['valueAfter'], $nextDepth);
            $output = [
                "$spaces  - {$difference['key']}: $stringValueBefore",
                "$spaces  + {$difference['key']}: $stringValueAfter"
            ];
            return implode("\n", $output);
        default:
            throw new \RuntimeException("Unknown type!");
    }
}

function getStringValue(mixed $value, int $depth): string
{
    return match (gettype($value)) {
        'NULL' => 'null',
        'boolean' => $value ? 'true' : 'false',
        'array' => convertArrayToString($value, $depth),
        default => "$value"
    };
}

function convertArrayToString(mixed $value, int $depth): string
{
    $keys = array_keys($value);
    $nextDepth = $depth + 1;

    $lines = array_map(function ($key) use ($value, $nextDepth) {
        $newValue = getStringValue($value[$key], $nextDepth);
        $spaces = makeSpace($nextDepth);
        return "$spaces$key: $newValue";
    }, $keys);
    $spaces = makeSpace($depth);

    $result = ['{', ...$lines, "$spaces}"];
    return implode("\n", $result);
}

function makeSpace(int $depth): string
{
    $idents = 4; // кол-во отступов
    $shift = 0; // сдвиг
    return str_repeat(' ', $depth * $idents - $shift);
}
