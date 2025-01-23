<?php

namespace Formatters\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath, string $format): array
{
    switch ($format) {
        case "json":
            return json_decode($filePath, true);
        case "yml":
        case "yaml":
            return Yaml::parse($filePath);
        default:
            throw new \RuntimeException("Error reading extension: {$format}");
    }
}
