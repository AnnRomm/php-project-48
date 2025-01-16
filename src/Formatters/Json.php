<?php

namespace Differ\Formatters\Json;

function formatJson(array $difference): string
{
    return json_encode($difference, JSON_PRETTY_PRINT) . "\n";
}
