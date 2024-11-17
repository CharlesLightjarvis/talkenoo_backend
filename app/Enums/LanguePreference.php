<?php

namespace App\Enums;

enum LanguePreference: string
{
    case FRANCAIS = 'fr';
    case ANGLAIS = 'en';
    case ESPAGNOL = 'es';
    case ALLEMAND = 'de';
    case ITALIEN = 'it';
    case PORTUGAIS = 'pt';

    public function label(): string
    {
        return match($this) {
            self::FRANCAIS => 'Français',
            self::ANGLAIS => 'Anglais',
            self::ESPAGNOL => 'Espagnol',
            self::ALLEMAND => 'Allemand',
            self::ITALIEN => 'Italien',
            self::PORTUGAIS => 'Portugais',
        };
    }
}
