<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{

    case Generated = 'GENERADO';

    case Processing = 'PROCESO';

    case Finished = 'FINALIZADO';

    case Cancelled = 'CANCELADO';

    public function getLabel(): string
    {
        return match ($this) {
            self::Generated => 'GENERADO',
            self::Processing => 'PROCESO',
            self::Finished => 'FINALIZADO',
            self::Cancelled => 'CANCELADO',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Generated => 'info',
            self::Processing => 'warning',
            self::Finished => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Generated => 'heroicon-m-sparkles',
            self::Processing => 'heroicon-m-arrow-path',
            self::Finished => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}