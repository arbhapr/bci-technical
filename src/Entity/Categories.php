<?php

namespace App\Entity;

enum Categories: string
{
    case EDUCATION = 'Education';
    case HEALTH = 'Health';
    case OFFICE = 'Office';
    case OTHERS = 'Others';

    public static function getValues(): array
    {
        return array_map(fn($category) => $category->value, self::cases());
    }
}