<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Json\formatJson;

function chooseFormatter(string $format, array $diff): string
{
    switch ($format) {
        case 'stylish':
            return formatStylish($diff);
        case 'plain':
            return formatPlain($diff);
        case 'json':
            return formatJson($diff);
        default:
            throw new \RuntimeException("Unsupported format: $format");
    }
}
