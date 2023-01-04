<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Goal implements CreatableFromArray
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $time,
        #[Assert\NotBlank]
        public readonly ?int $scorer,
        public readonly bool $isPenaltyKick,
        public readonly array $assists = [],
    ) {
    }

    public static function createFromArray(array $data): self
    {
        $assists = \array_map(static fn ($assist) => $assist, $data['P'] ?? []);

        return new self(
            $data['Laiks'] ?? null,
            $data['Nr'] ?? null,
            $data['Sitiens'] === 'J',
            \array_column($assists, 'Nr')
        );
    }
}
