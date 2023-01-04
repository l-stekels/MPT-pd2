<?php

declare(strict_types=1);

namespace App\DTO;

class TeamStatistics
{
    public function __construct(
        public readonly int $place,
        public readonly string $name,
        public readonly int $points,
        public readonly int $onTimeWins,
        public readonly int $onTimeLoses,
        public readonly int $overTimeWins,
        public readonly int $overTimeLoses,
        public readonly int $goalsScored,
        public readonly int $goalsLost,
    ) {
    }
}
