<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Substitute implements CreatableFromArray
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $time,
        #[Assert\NotBlank]
        public readonly ?int $from,
        #[Assert\NotBlank]
        public readonly ?int $to,
    ) {
    }


    public static function createFromArray(array $data): self
    {
        return new self(
            $data['Laiks'] ?? null,
            $data['Nr1'] ?? null,
            $data['Nr2'] ?? null,
        );
    }
}
