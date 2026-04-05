<?php

declare(strict_types=1);

namespace App\Enum;

enum Status: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}
