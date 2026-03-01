<?php

namespace App\Enums;

enum ContentStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Taslak',
            self::Published => 'Yayında',
            self::Archived => 'Arşivlendi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'yellow',
            self::Published => 'green',
            self::Archived => 'gray',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
