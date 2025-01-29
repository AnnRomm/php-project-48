<?php

namespace Formatters\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string|false $currentData, string $format): array
{
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
