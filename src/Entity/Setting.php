<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SettingRepository;
use App\Types\Settings\SettingsValueType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $key;

    #[ORM\Column(enumType: SettingsValueType::class)]
    private ?SettingsValueType $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private mixed $value = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $required = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getType(): ?SettingsValueType
    {
        return $this->type;
    }

    public function setType(SettingsValueType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): static
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        }

        $this->value = $value;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
