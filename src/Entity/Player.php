<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enum\PlayerRole;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Index(fields: ['number'])]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(enumType: PlayerRole::class)]
    private PlayerRole $role;

    #[ORM\Column]
    private int $number;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $team;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Penalty::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $penalties;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Goal::class, orphanRemoval: true)]
    private Collection $goals;

    #[ORM\ManyToMany(targetEntity: Goal::class, mappedBy: 'assistants', cascade: ['persist'])]
    private Collection $assists;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'players', cascade: ['persist'])]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'newPlayer', targetEntity: Substitution::class, cascade: ['persist'])]
    private Collection $gamesSubstituted;

    public function __construct(
        string $firstName,
        string $lastName,
        PlayerRole $role,
        int $number,
        Team $team,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->role = $role;
        $this->number = $number;
        $this->team = $team;
        $team->addPlayer($this);
        $this->penalties = new ArrayCollection();
        $this->goals = new ArrayCollection();
        $this->assists = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->gamesSubstituted = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getRole(): PlayerRole
    {
        return $this->role;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return Collection<int, Penalty>
     */
    public function getPenalties(): Collection
    {
        return $this->penalties;
    }

    public function addPenalty(Penalty $penalty): void
    {
        if (!$this->penalties->contains($penalty)) {
            $this->penalties->add($penalty);
        }
    }

    /**
     * @return Collection<int, Goal>
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): void
    {
        if (!$this->goals->contains($goal)) {
            $this->goals->add($goal);
        }
    }

    /**
     * @return Collection<int, Goal>
     */
    public function getAssists(): Collection
    {
        return $this->assists;
    }

    public function addAssist(Goal $assist): void
    {
        if (!$this->assists->contains($assist)) {
            $this->assists->add($assist);
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

    public function getGamesSubstituted(): Collection
    {
        return $this->gamesSubstituted;
    }

    public function addGameSubstitution(Substitution $substitution): void
    {
        if (!$this->gamesSubstituted->contains($substitution)) {
            $this->gamesSubstituted->add($substitution);
        }
    }
}
