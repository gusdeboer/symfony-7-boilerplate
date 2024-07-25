<?php

declare(strict_types=1);

namespace App\Types\Settings;

enum SettingKeyType: string
{
    case SettingOne = 'setting_one';
    case SettingTwo = 'setting_two';
    case SettingThree = 'setting_three';
    case SettingFour = 'setting_four';
    case SettingFive = 'setting_five';
}
