<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Game implements CreatableFromArray
{
    /**
     * @param Referee[] $referees
     * @param Team[] $teams
     */
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?\DateTimeImmutable $date = null,
        public readonly int $viewers = 0,
        public readonly ?string $stadium = null,
        public readonly array $referees = [],
        #[Assert\NotBlank]
        #[Assert\Count(2)]
        #[Assert\Valid]
        public readonly array $teams = [],
    ) {
    }

    public static function createFromArray(array $data): self
    {
        $gameData = $data['Spele'] ?? [];

        $dateString = $gameData['Laiks'] ?? null;
        $referees = [];
        foreach ($gameData['T'] ?? [] as $refereeData) {
            $referees[] = new Referee(
                $refereeData['Vards'] ?? null,
                $refereeData['Uzvards'] ?? null,
            );
        }
        if (isset($gameData['VT'])) {
            $referees[] = new Referee(
                $gameData['VT']['Vards'] ?? null,
                $gameData['VT']['Uzvards'] ?? null,
                true
            );
        }
        $teams = [];
        foreach ($gameData['Komanda'] ?? []  as $teamData) {
            $teams[] = Team::createFromArray($teamData);
        }

        return new self(
            $dateString ? new \DateTimeImmutable($dateString) : null,
            $gameData['Skatitaji'] ?? 0,
            $gameData['Vieta'] ?? null,
            $referees,
            $teams,
        );
    }
}
