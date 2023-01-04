<?php

declare(strict_types=1);

namespace App\DTO;

class PlayerStatistics
{
    public function __construct(
        public readonly int $place,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $number,
        public readonly string $team,
        public readonly int $goals,
        public readonly int $assists,
    ) {
    }
}
