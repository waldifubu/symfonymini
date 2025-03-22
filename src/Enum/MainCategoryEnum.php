<?php
declare(strict_types=1);

namespace App\Enum;

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

    public static function validKeys(): array
    {
        return array_column(self::cases(), 'name');
    }

    // Resolve enum case by KEY (case name)
    public static function tryFromName(string $name): ?self
    {
        return array_reduce(self::cases(), fn(?self $carry, self $case) => $case->name === $name ? $case : $carry, null);
    }

    /**
     * @return array<string,string>
     */
    public static function getAsArray(): array
    {
        return array_reduce(
            self::cases(),
            static fn(array $choices, MainCategoryEnum $type) => $choices + [$type->name => $type->value],
            [],
        );
    }
}

