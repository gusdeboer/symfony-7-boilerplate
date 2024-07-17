<?php

declare(strict_types=1);

namespace App\Types\Settings;

enum SettingsValueType: string
{
    case String = 'string';
    case Integer = 'integer';
    case Boolean = 'boolean';
    case Date = 'date';
    case Json = 'json';
}
