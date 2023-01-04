<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GoalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoalRepository::class)]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'goals')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $player;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'assists')]
    private Collection $assistants;

    #[ORM\Column]
    private int $minutes;

    #[ORM\Column]
    private int $seconds;

    #[ORM\Column]
    private bool $isPenaltyKick;

    #[ORM\ManyToOne(inversedBy: 'goals')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    /**
     * @param Player[] $assistants
     */
    public function __construct(
        Player $player,
        array $assistants,
        int $minutes,
        int $seconds,
        bool $isPenaltyKick,
        Game $game,
    ) {
        $this->player = $player;
        $player->addGoal($this);
        $this->assistants = new ArrayCollection($assistants);
//        foreach ($assistants as $assistant) {
//            $assistant->addAssist($this);
//        }
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->isPenaltyKick = $isPenaltyKick;
        $this->game = $game;
        $game->addGoal($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getAssistants(): Collection
    {
        return $this->assistants;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }

    public function isIsPenaltyKick(): bool
    {
        return $this->isPenaltyKick;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}
