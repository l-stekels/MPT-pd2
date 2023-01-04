<?php

declare(strict_types=1);

namespace App\DTO;

class Referee
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly bool $isMain = false,
    ) {
    }
}
