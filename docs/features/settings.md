# Settings

A basic settings module that allows you to manage settings through the database.

## Usage

Setting can be managed by through the database by adding rows
to the `settings` table. The `settings` table has the following columns:

- `id` - The unique identifier for the setting.
- `key` - The name of the setting.
- `type` - The type of the setting. The following types are supported, see `SettingsValueTypes`
- `value` - The value of the setting.
- `required` - If the setting is required.
- `updated_at` - The date the setting was last updated.

## Form

The form type can be altered in `src/Form/SettingType.php`

## Setting Keys

Setting keys are used to identify the setting in the application. The key should be unique
and should be used to reference the setting in the application.

Settings depend on the key to be able to retrieve the setting value.

## Setting Value Types

The following value types are supported:

- `string` - A string value.
- `integer` - An integer value.
- `boolean` - A boolean value.
- `date` - A date value.
- `json` - A JSON value.

More value types can be added by extending the `SettingsValueTypes` class.

## Settings Service

The `SettingsService` class is used to retrieve settings from the database.
The service can be used to retrieve settings by key.

```php
use App\Services\SettingsService;
use App\Types\Settings\SettingKeyType;

readonly class FooBarController {
    
    public function __construct(
        private SettingsService $settingsService
    ) {}

    public function index(): void
    {
        $settingOne = $this->settingsService->getSetting(SettingKeyType::SettingOne);
    }
}
```

## Translations

Translations for specific settings can be added to the `translations/messages.en.yml`
And are based on the `key` of the setting.

```yaml
settings:
    setting_key: "Setting Name"
```

## Adding a new setting

To add a new service simply write a new migration to add the setting to the database.
Then add the setting key to the `SettingKeyType` class.

```sql
INSERT INTO setting (`id`, `key`, `type`, `value`, `required`, `updated_at`) VALUES (1, 'setting_one', 'string', '1', true, '2024-07-25 18:33:21');
INSERT INTO setting (`id`, `key`, `type`, `value`, `required`, `updated_at`) VALUES (2, 'setting_two', 'integer', null, false, '2024-07-25 18:33:21');
INSERT INTO setting (`id`, `key`, `type`, `value`, `required`, `updated_at`) VALUES (3, 'setting_three', 'boolean', '1', true, '2024-07-25 18:33:21');
INSERT INTO setting (`id`, `key`, `type`, `value`, `required`, `updated_at`) VALUES (4, 'setting_four', 'date', null, false, '2024-07-25 18:33:21');
INSERT INTO setting (`id`, `key`, `type`, `value`, `required`, `updated_at`) VALUES (5, 'setting_five', 'json', '{}', false, '2024-07-25 18:33:21');
```