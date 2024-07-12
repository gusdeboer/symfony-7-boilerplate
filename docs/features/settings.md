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

## Translations

Translations for specific settings can be added to the `translations/messages.en.yml`
And are based on the `key` of the setting.

```yaml
settings:
    setting_key: "Setting Name"
```