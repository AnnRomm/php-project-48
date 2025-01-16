<?php

namespace Differ\Formatters\Plain;

function formatPlain(array $difference): string
{
    return formatDiff($difference) . "\n";
}
function formatDiff(array $difference, array $path = []): string
{
    $key = $difference['key'] ?? null;
    $currentPath = $key !== null ? array_merge($path, [$key]) : $path;
    $stringPath = implode(".", $currentPath);

    switch ($difference['status']) {
        case 'root':
        case 'node':
            $lines = array_map(function ($item) use ($currentPath) {
                return formatDiff($item, $currentPath);
            }, $difference['value']);
            return implode("\n", array_filter($lines));
        case 'added':
            $stringValueAfter = getStringValue($difference['value']);
            return sprintf("Property '%s' was added with value: %s", $stringPath, $stringValueAfter);
        case 'deleted':
            return sprintf("Property '%s' was removed", $stringPath);
        case 'unchanged':
            return '';
        case 'changed':
            $stringValueBefore = getStringValue($difference['valueBefore']);
            $stringValueAfter = getStringValue($difference['valueAfter']);
            return sprintf(
                "Property '%s' was updated. From %s to %s",
                $stringPath,
                $stringValueBefore,
                $stringValueAfter
            );
        default:
            throw new \RuntimeException("Unknown type!");
    }
}
function getStringValue(mixed $value): string
{
    return match (gettype($value)) {
        'NULL' => 'null',
        'boolean' => $value ? 'true' : 'false',
        'array', 'object' => '[complex value]',
        'string' => "'$value'",
        default => "$value"
    };
}
