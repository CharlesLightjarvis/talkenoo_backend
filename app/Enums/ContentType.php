<?php

namespace App\Enums;

enum ContentType: string
{
    case TEXTE = 'TEXTE';
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
    case AUDIO = 'AUDIO';
    case PDF = 'PDF';
    case QUIZ = 'QUIZ';
    case EXERCICE = 'EXERCICE';

    public function label(): string
    {
        return match($this) {
            self::IMAGE => 'Image',
            self::VIDEO => 'Vidéo',
            self::AUDIO => 'Audio',
            self::PDF => 'PDF',
            self::QUIZ => 'Quiz',
            self::EXERCICE => 'Exercice',
        };
    }
}
