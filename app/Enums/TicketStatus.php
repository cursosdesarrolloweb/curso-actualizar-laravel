<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'open';
    case Pending = 'pending';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Abierto',
            self::Pending => 'Esperando al cliente',
            self::Closed => 'Cerrado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Open => 'bg-green-100 text-green-800',
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Closed => 'bg-gray-100 text-gray-700',
        };
    }
}
