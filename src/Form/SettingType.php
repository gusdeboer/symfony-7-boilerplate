<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Setting;
use App\Types\Settings\SettingsValueType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        Assert::allIsInstanceOf($options['settings'], Setting::class);

        /** @var Setting $setting */
        foreach ($options['settings'] as $setting) {
            $type = match ($setting->getType()) {
                SettingsValueType::String => null,
                SettingsValueType::Integer => NumberType::class,
                SettingsValueType::Boolean => CheckboxType::class,
                SettingsValueType::Json => TextType::class,
                SettingsValueType::Date => DateType::class,
            };

            $options = match ($setting->getType()) {
                SettingsValueType::String, SettingsValueType::Json, SettingsValueType::Boolean, => [],
                SettingsValueType::Date, SettingsValueType::Integer => ['html5' => true],
            };

            $value = $setting->getValue();

            if (SettingsValueType::Boolean === $setting->getType()) {
                $value = '1' === $setting->getValue();
            }

            if (SettingsValueType::Date === $setting->getType() && is_string($setting->getValue())) {
                $value = new \DateTime($setting->getValue());
            }

            $builder->add($setting->getKey()->value, $type, [
                'label' => sprintf('settings.%s', $setting->getKey()->value),
                'data' => $value,
                'required' => $setting->isRequired(),
            ] + $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'settings' => null,
        ]);
    }
}
