<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PenaltyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PenaltyRepository::class)]
class Penalty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'penalties')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $player;

    #[ORM\Column]
    private int $minutes;

    #[ORM\Column]
    private int $seconds;

    #[ORM\ManyToOne(inversedBy: 'penalties')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    public function __construct(
        Player $player,
        int $minutes,
        int $seconds,
        Game $game,
    ) {
        $this->player = $player;
        $player->addPenalty($this);
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->game = $game;
        $game->addPenalty($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
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
