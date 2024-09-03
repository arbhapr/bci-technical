<?php

namespace App\Entity;

enum Stages: string
{
    case CONCEPT = 'Concept';
    case DESIGN = 'Design & Documentation';
    case PRECONST = 'Pre-Construction';
    case CONST = 'Construction';

    public static function getValues(): array
    {
        return array_map(fn($stage) => $stage->value, self::cases());
    }
}