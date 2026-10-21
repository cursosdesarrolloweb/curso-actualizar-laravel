<?php

namespace App\Enums;

enum TicketPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Baja',
            self::Normal => 'Normal',
            self::High => 'Alta',
        };
    }

    public function slaHours(): int
    {
        return match ($this) {
            self::Low => 72,
            self::Normal => 24,
            self::High => 8,
        };
    }
}
