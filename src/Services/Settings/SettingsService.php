<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Webmozart\Assert\Assert;

final readonly class SettingsService
{
    public function __construct(
        private SettingRepository $settingRepository
    ) {
    }

    public function getSetting(string $key): Setting
    {
        $setting = $this->settingRepository->findBy(['key' => $key]);

        Assert::count($setting, 1, 'Setting not found');
        Assert::isInstanceOf($setting[0], Setting::class);

        return $setting[0];
    }

    /**
     * @param array<string> $keys
     *
     * @return array<Setting>
     */
    public function getManySettings(array $keys): array
    {
        $settings = $this->settingRepository->findBy(['key' => $keys]);

        Assert::minCount($settings, 1, 'No settings found');
        Assert::allIsInstanceOf($settings, Setting::class);

        return $settings;
    }
}
