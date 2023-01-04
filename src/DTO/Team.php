<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Team implements CreatableFromArray
{
    /**
     * @param Player[]     $players
     * @param Goal[]       $goals
     * @param Substitute[] $substitutes
     * @param Penalty[] $penalties
     */
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $name = null,
        #[Assert\NotBlank]
        #[Assert\Valid]
        public readonly array $players = [],
        #[Assert\NotBlank]
        #[Assert\All([new Assert\Type('int')])]
        public readonly array $playersOnFieldAtStart = [],
        #[Assert\Valid]
        public readonly array $goals = [],
        #[Assert\Valid]
        public readonly array $substitutes = [],
        #[Assert\Valid]
        public readonly array $penalties = [],
    ) {
    }

    public static function createFromArray(array $data): CreatableFromArray
    {
        $players = [];
        foreach ($data['Speletaji']['Speletajs'] ?? [] as $playerData) {
            $players[] = Player::createFromArray($playerData);
        }
        $playersOnFieldAtStart = [];
        foreach ($data['Pamatsastavs']['Speletajs'] ?? [] as $playerData) {
            $playersOnFieldAtStart[] = $playerData['Nr'];
        }

        $goals = [];
        if (isset($data['Varti']['VG']['Laiks'])) {
            $goals[] = Goal::createFromArray($data['Varti']['VG']);
        } elseif (is_array($data['Varti']['VG'] ?? null)) {
            foreach ($data['Varti']['VG'] as $goalData) {
                $goals[] = Goal::createFromArray($goalData);
            }
        }

        $substitutes = [];
        if (isset($data['Mainas']['Maina']['Laiks'])) {
            $substitutes[] = Substitute::createFromArray($data['Mainas']['Maina']);
        } elseif (is_array($data['Mainas']['Maina'] ?? null)) {
            foreach ($data['Mainas']['Maina'] as $maina) {
                $substitutes[] = Substitute::createFromArray($maina);
            }
        }

        $penalties = [];
        if (isset($data['Sodi']['Sods']['Laiks'])) {
            $penalties[] = Penalty::createFromArray($data['Sodi']['Sods']);
        } elseif (is_array($data['Sodi']['Sods'] ?? null)) {
            foreach ($data['Sodi']['Sods'] as $maina) {
                $penalties[] = Penalty::createFromArray($maina);
            }
        }

        return new self(
            $data['Nosaukums'] ?? null,
            $players,
            $playersOnFieldAtStart,
            $goals,
            $substitutes,
            $penalties,
        );
    }
}
