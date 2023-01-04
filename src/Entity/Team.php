<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\Index(fields: ['name'])]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $players;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'teams', cascade: ['persist'])]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Statistics::class, orphanRemoval: true)]
    private Collection $statistics;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->players = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): void
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): void
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
        }
    }

    /**
     * @return Collection<int, Statistics>
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistics $statistics): void
    {
        if (!$this->statistics->contains($statistics)) {
            $this->statistics->add($statistics);
        }
    }
}
