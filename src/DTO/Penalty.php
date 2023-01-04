<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Penalty implements CreatableFromArray
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $time,
        #[Assert\NotBlank]
        public readonly ?int $player,
    ) {
    }

    public static function createFromArray(array $data): CreatableFromArray
    {
        return new self(
            $data['Laiks'] ?? null,
            $data['Nr'] ?? null,
        );
    }
}
