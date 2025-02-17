<?php
declare(strict_types=1);

namespace App\DBAL;

enum MainCategoryEnum: string
{
    case NONE = 'None';
    case ARTS = 'Arts';
    case BUSINESS = 'Business';
    case COMEDY = 'Comedy';
    case EDUCATION = 'Education';
    case FICTION = 'Fiction';
    case GOVERNMENT = 'Government';
    case HISTORY = 'History';
    case HEALTH_FITNESS = 'Health & Fitness';
    case KIDS_FAMILY = 'Kids & Family';
    case LEISURE = 'Leisure';
    case MUSIC = 'Music';
    case NEWS = 'News';
    case RELIGION_SPIRIT = 'Religion & Spirituality';
    case SCIENCE = 'Science';
    case SOCIETY_CULTURE = 'Society & Culture';
    case SPORTS = 'Sports';
    case TECHNOLOGY = 'Technology';
    case TRUE_CRIME = 'True Crime';
    case TV_FILM = 'TV & Film';

    /**
     * @return array<string,string>
     */
    public static function getAsArray(): array
    {
        return array_reduce(
            self::cases(),
            static fn (array $choices, MainCategoryEnum $type) => $choices + [$type->name => $type->value],
            [],
        );
    }
}

