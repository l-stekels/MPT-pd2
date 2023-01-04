<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Player implements CreatableFromArray
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $role = null,
        #[Assert\NotBlank]
        public readonly ?int $number = null,
        #[Assert\NotBlank]
        public readonly ?string $firstName = null,
        #[Assert\NotBlank]
        public readonly ?string $lastName = null,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            $data['Loma'] ?? null,
            $data['Nr'] ?? null,
            $data['Vards'] ?? null,
            $data['Uzvards'] ?? null,
        );
    }
}
