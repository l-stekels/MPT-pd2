<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum PlayerRole: string
{
    case GOALIE = 'V';
    case DEFENDER = 'A';
    case FORWARD = 'U';
}
