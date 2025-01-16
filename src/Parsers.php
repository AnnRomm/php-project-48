<?php

namespace Formatters\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): array
{
    $extension = pathinfo($filePath)['extension'] ?? null;
    $basename = pathinfo($filePath)['basename'];
    if (is_readable($filePath)) {
        return match ($extension) {
            'json' => parseJson($filePath),
            'yaml', 'yml' => parseYaml($filePath)
        };
    } else {
        throw new \RuntimeException("Error reading file: $basename");
    }
}

function parseJson(string $filePath): array
{
    $fileContent = file_get_contents($filePath);
    return json_decode($fileContent ?: '', true) ?? [];
}

function parseYaml(string $filePath): array
{
    $fileContent = file_get_contents($filePath);
    return Yaml::parse($fileContent ?: '') ?? [];
}
