<?php

namespace App\DBAL;

enum SubCategoryEnum: string
{
    // Arts
    case BOOKS = 'Books';
    case DESIGN = 'Design';
    case FASHION_AND_BEAUTY = 'Fashion & Beauty';
    case FOOD = 'Food';
    case PERFORMING_ARTS = 'Performing Arts';
    case VISUAL_ARTS = 'Visual Arts';

    public static function getArts(): array
    {
        return [
            self::BOOKS->name => self::BOOKS,
            self::DESIGN->name => self::DESIGN,
            self::FASHION_AND_BEAUTY->name => self::FASHION_AND_BEAUTY,
            self::FOOD->name => self::FOOD,
            self::PERFORMING_ARTS->name => self::PERFORMING_ARTS,
            self::VISUAL_ARTS->name => self::VISUAL_ARTS
        ];
    }

    // Business
    case CAREERS = 'Careers';
    case ENTREPRENEURSHIP = 'Entrepreneurship';
    case INVESTING = 'Investing';
    case MANAGEMENT = 'Management';
    case MARKETING = 'Marketing';
    case NON_PROFIT = 'Non-Profit';

    public static function getBusiness(): array
    {
        return [
            self::CAREERS->name => self::CAREERS,
            self::ENTREPRENEURSHIP->name => self::ENTREPRENEURSHIP,
            self::INVESTING->name => self::INVESTING,
            self::MANAGEMENT->name => self::MANAGEMENT,
            self::MARKETING->name => self::MARKETING,
            self::NON_PROFIT->name => self::NON_PROFIT
        ];
    }

    // Comedy
    case COMEDY_INTERVIEWS = 'Comedy Interviews';
    case IMPROV = 'Improv';
    case STAND_UP = 'Stand-Up';

    public static function getComedy(): array
    {
        return [
            self::COMEDY_INTERVIEWS->name => self::COMEDY_INTERVIEWS,
            self::IMPROV->name => self::IMPROV,
            self::STAND_UP->name => self::STAND_UP
        ];
    }

    // Education
    case COURSES = 'Courses';
    case HOW_TO = 'How To';
    case LANGUAGE_LEARNING = 'Language Learning';
    case SELF_IMPROVEMENT = 'Self-Improvement';
    // Fiction
    case COMEDY_FICTION = 'Comedy Fiction';
    case DRAMA = 'Drama';
    case SCIENCE_FICTION = 'Science Fiction';

    public static function getFiction(): array
    {
        return [
            self::COMEDY_FICTION->name => self::COMEDY_FICTION,
            self::DRAMA->name => self::DRAMA,
            self::SCIENCE_FICTION->name => self::SCIENCE_FICTION
        ];
    }

    // Health & Fitness
    case ALTERNATIVE_HEALTH = 'Alternative Health';
    case FITNESS = 'Fitness';
    case MEDICINE = 'Medicine';
    case MENTAL_HEALTH = 'Mental Health';
    case NUTRITION = 'Nutrition';
    case SEXUALITY = 'Sexuality';

    public static function getHealthFitness(): array
    {
        return [
            self::ALTERNATIVE_HEALTH->name => self::ALTERNATIVE_HEALTH,
            self::FITNESS->name => self::FITNESS,
            self::MEDICINE->name => self::MEDICINE,
            self::MENTAL_HEALTH->name => self::MENTAL_HEALTH,
            self::NUTRITION->name => self::NUTRITION,
            self::SEXUALITY->name => self::SEXUALITY
        ];
    }

    // Kids & Family
    case EDUCATION_FOR_KIDS = 'Education for Kids';
    case PARENTING = 'Parenting';
    case PETS_AND_ANIMALS = 'Pets & Animals';
    case STORIES_FOR_KIDS = 'Stories for Kids';

    public static function getKidsFamily(): array
    {
        return [
            self::EDUCATION_FOR_KIDS->name => self::EDUCATION_FOR_KIDS,
            self::PARENTING->name => self::PARENTING,
            self::PETS_AND_ANIMALS->name => self::PETS_AND_ANIMALS,
            self::STORIES_FOR_KIDS->name => self::STORIES_FOR_KIDS
        ];
    }

    // Leisure
    case ANIMATION_AND_MANGA = 'Animation & Manga';
    case AUTOMOTIVE = 'Automotive';
    case AVIATION = 'Aviation';
    case CRAFTS = 'Crafts';
    case GAMES = 'Games';
    case HOBBIES = 'Hobbies';
    case HOME_AND_GARDEN = 'Home & Garden';
    case VIDEO_GAMES = 'Video Games';

    public static function getLeisure(): array
    {
        return [
            self::ANIMATION_AND_MANGA->name => self::ANIMATION_AND_MANGA,
            self::AUTOMOTIVE->name => self::AUTOMOTIVE,
            self::AVIATION->name => self::AVIATION,
            self::CRAFTS->name => self::CRAFTS,
            self::GAMES->name => self::GAMES,
            self::HOBBIES->name => self::HOBBIES,
            self::HOME_AND_GARDEN->name => self::HOME_AND_GARDEN,
            self::VIDEO_GAMES->name => self::VIDEO_GAMES
        ];
    }

    // Music
    case MUSIC_COMMENTARY = 'Music Commentary';
    case MUSIC_HISTORY = 'Music History';
    case MUSIC_INTERVIEWS = 'Music Interviews';

    public static function getMusic(): array
    {
        return [
            self::MUSIC_COMMENTARY->name => self::MUSIC_COMMENTARY,
            self::MUSIC_HISTORY->name => self::MUSIC_HISTORY,
            self::MUSIC_INTERVIEWS->name => self::MUSIC_INTERVIEWS
        ];
    }

    // News
    case BUSINESS_NEWS = 'Business News';
    case DAILY_NEWS = 'Daily News';
    case ENTERTAINMENT_NEWS = 'Entertainment News';
    case NEWS_COMMENTARY = 'News Commentary';
    case POLITICS = 'Politics';
    case SPORTS_NEWS = 'Sports News';
    case TECH_NEWS = 'Tech News';

    public static function getNews(): array
    {
        return [
            self::BUSINESS_NEWS->name => self::BUSINESS_NEWS,
            self::DAILY_NEWS->name => self::DAILY_NEWS,
            self::ENTERTAINMENT_NEWS->name => self::ENTERTAINMENT_NEWS,
            self::NEWS_COMMENTARY->name => self::NEWS_COMMENTARY,
            self::POLITICS->name => self::POLITICS,
            self::SPORTS_NEWS->name => self::SPORTS_NEWS,
            self::TECH_NEWS->name => self::TECH_NEWS
        ];
    }

    // Religion & Spirituality
    case BUDDHISM = 'Buddhism';
    case CHRISTIANITY = 'Christianity';
    case HINDUISM = 'Hinduism';
    case ISLAM = 'Islam';
    case JUDAISM = 'Judaism';
    case RELIGION = 'Religion';
    case SPIRITUALITY = 'Spirituality';

    public static function getReligionSpirit(): array
    {
        return [
            self::BUDDHISM->name => self::BUDDHISM,
            self::CHRISTIANITY->name => self::CHRISTIANITY,
            self::HINDUISM->name => self::HINDUISM,
            self::ISLAM->name => self::ISLAM,
            self::JUDAISM->name => self::JUDAISM,
            self::RELIGION->name => self::RELIGION,
            self::SPIRITUALITY->name => self::SPIRITUALITY
        ];
    }

    // Science
    case ASTRONOMY = 'Astronomy';
    case CHEMISTRY = 'Chemistry';
    case EARTH_SCIENCES = 'Earth Sciences';
    case LIFE_SCIENCES = 'Life Sciences';
    case MATHEMATICS = 'Mathematics';
    case NATURAL_SCIENCES = 'Natural Sciences';
    case NATURE = 'Nature';
    case PHYSICS = 'Physics';
    case SOCIAL_SCIENCES = 'Social Sciences';

    public static function getScience(): array
    {
        return [
            self::ASTRONOMY->name => self::ASTRONOMY,
            self::CHEMISTRY->name => self::CHEMISTRY,
            self::EARTH_SCIENCES->name => self::EARTH_SCIENCES,
            self::LIFE_SCIENCES->name => self::LIFE_SCIENCES,
            self::MATHEMATICS->name => self::MATHEMATICS,
            self::NATURAL_SCIENCES->name => self::NATURAL_SCIENCES,
            self::NATURE->name => self::NATURE,
            self::PHYSICS->name => self::PHYSICS,
            self::SOCIAL_SCIENCES->name => self::SOCIAL_SCIENCES
        ];
    }

    // Society & Culture
    case DOCUMENTARY = 'Documentary';
    case PERSONAL_JOURNALS = 'Personal Journals';
    case PHILOSOPHY = 'Philosophy';
    case PLACES_AND_TRAVEL = 'Places & Travel';
    case RELATIONSHIPS = 'Relationships';

    public static function getSocietyCulture(): array
    {
        return [
            self::DOCUMENTARY->name => self::DOCUMENTARY,
            self::PERSONAL_JOURNALS->name => self::PERSONAL_JOURNALS,
            self::PHILOSOPHY->name => self::PHILOSOPHY,
            self::PLACES_AND_TRAVEL->name => self::PLACES_AND_TRAVEL,
            self::RELATIONSHIPS->name => self::RELATIONSHIPS
        ];
    }

    // Sports
    case BASEBALL = 'Baseball';
    case BASKETBALL = 'Basketball';
    case CRICKET = 'Cricket';
    case FANTASY_SPORTS = 'Fantasy Sports';
    case FOOTBALL = 'Football';
    case GOLF = 'Golf';
    case HOCKEY = 'Hockey';
    case RUGBY = 'Rugby';
    case RUNNING = 'Running';
    case SOCCER = 'Soccer';
    case SWIMMING = 'Swimming';
    case TENNIS = 'Tennis';
    case VOLLEYBALL = 'Volleyball';
    case WILDERNESS = 'Wilderness';
    case WRESTLING = 'Wrestling';

    public static function getSports(): array
    {
        return [
            self::BASEBALL->name => self::BASEBALL,
            self::BASKETBALL->name => self::BASKETBALL,
            self::CRICKET->name => self::CRICKET,
            self::FANTASY_SPORTS->name => self::FANTASY_SPORTS,
            self::FOOTBALL->name => self::FOOTBALL,
            self::GOLF->name => self::GOLF,
            self::HOCKEY->name => self::HOCKEY,
            self::RUGBY->name => self::RUGBY,
            self::RUNNING->name => self::RUNNING,
            self::SOCCER->name => self::SOCCER,
            self::SWIMMING->name => self::SWIMMING,
            self::TENNIS->name => self::TENNIS,
            self::VOLLEYBALL->name => self::VOLLEYBALL,
            self::WILDERNESS->name => self::WILDERNESS,
            self::WRESTLING->name => self::WRESTLING
        ];
    }

    // TV & Film
    case AFTER_SHOWS = 'After Shows';
    case FILM_HISTORY = 'Film History';
    case FILM_INTERVIEWS = 'Film Interviews';
    case FILM_REVIEWS = 'Film Reviews';
    case TV_REVIEWS = 'TV Reviews';
    case NONE = 'No subcategory';

    public static function getTVFilm(): array
    {
        return [
            self::AFTER_SHOWS->name => self::AFTER_SHOWS,
            self::FILM_HISTORY->name => self::FILM_HISTORY,
            self::FILM_INTERVIEWS->name => self::FILM_INTERVIEWS,
            self::FILM_REVIEWS->name => self::FILM_REVIEWS,
            self::TV_REVIEWS->name => self::TV_REVIEWS,
        ];
    }
}