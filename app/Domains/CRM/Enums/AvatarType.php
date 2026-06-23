<?php

namespace App\Domains\CRM\Enums;

enum AvatarType: string
{
    case Depleted = 'depleted';
    case Frustrated = 'frustrated';
    case Confidence = 'confidence';

    public function label(): string
    {
        return match ($this) {
            self::Depleted => 'Depleted',
            self::Frustrated => 'Frustrated',
            self::Confidence => 'Confidence',
        };
    }

    public function configKey(): string
    {
        return $this->value;
    }
}
