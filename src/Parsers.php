<?php

namespace Formatters\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string|false $currentData, string $format): array
{
    if ($currentData === false) {
        throw new \RuntimeException("Invalid file content provided for parsing.");
    }
    switch ($format) {
        case "json":
            return json_decode($currentData, true);
        case "yml":
        case "yaml":
            return Yaml::parse($currentData);
        default:
            throw new \RuntimeException("Error reading extension: {$format}");
    }
}
