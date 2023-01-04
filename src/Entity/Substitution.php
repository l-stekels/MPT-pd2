<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubstitutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstitutionRepository::class)]
class Substitution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'substitutions')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $playerSubstituted;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Player $newPlayer;

    #[ORM\Column]
    private int $minutes;

    #[ORM\Column]
    private int $seconds;

    #[ORM\ManyToOne(inversedBy: 'substitutions')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    public function __construct(
        Player $playerSubstituted,
        Player $newPlayer,
        int $minutes,
        int $seconds,
        Game $game,
    ) {
        $this->playerSubstituted = $playerSubstituted;
        $this->newPlayer = $newPlayer;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->game = $game;
        $game->addSubstitution($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayerSubstituted(): Player
    {
        return $this->playerSubstituted;
    }

    public function getNewPlayer(): Player
    {
        return $this->newPlayer;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}
