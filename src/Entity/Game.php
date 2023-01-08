<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private \DateTimeImmutable $date;

    #[ORM\Column]
    private int $viewers;

    #[ORM\Column(length: 255)]
    private string $stadium;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'mainGames')]
    #[ORM\JoinColumn(nullable: false)]
    private Referee $mainReferee;

    #[ORM\ManyToMany(targetEntity: Referee::class, inversedBy: 'assistedGames', cascade: ['persist'])]
    private Collection $assistantReferees;

    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'games')]
    private Collection $teams;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Penalty::class, orphanRemoval: true)]
    #[ORM\OrderBy(['minutes' => 'ASC', 'seconds' => 'ASC'])]
    private Collection $penalties;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'games')]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Goal::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['minutes' => 'ASC', 'seconds' => 'ASC'])]
    private Collection $goals;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Substitution::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['minutes' => 'ASC', 'seconds' => 'ASC'])]
    private Collection $substitutions;

    public function __construct(
        \DateTimeImmutable $date,
        int $viewers,
        string $stadium,
    ) {
        $this->date = $date;
        $this->viewers = $viewers;
        $this->stadium = $stadium;
        $this->assistantReferees = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->penalties = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->goals = new ArrayCollection();
        $this->substitutions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getViewers(): int
    {
        return $this->viewers;
    }


    public function getStadium(): string
    {
        return $this->stadium;
    }

    public function getMainReferee(): Referee
    {
        return $this->mainReferee;
    }

    public function setMainReferee(Referee $mainReferee): void
    {
        $this->mainReferee = $mainReferee;
        $mainReferee->addMainGame($this);
    }

    /**
     * @return Collection<int, Referee>
     */
    public function getAssistantReferees(): Collection
    {
        return $this->assistantReferees;
    }

    public function addAssistantReferee(Referee $assistantReferee): void
    {
        if (!$this->assistantReferees->contains($assistantReferee)) {
            $this->assistantReferees->add($assistantReferee);
            $assistantReferee->addAssistedGame($this);
        }
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): void
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addGame($this);
        }
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
            $player->addGame($this);
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
     * @return Collection<int, Substitution>
     */
    public function getSubstitutions(): Collection
    {
        return $this->substitutions;
    }

    public function addSubstitution(Substitution $substitution): void
    {
        if (!$this->substitutions->contains($substitution)) {
            $this->substitutions->add($substitution);
        }
    }

    /** Total game time in seconds */
    public function getTotalGameTime(): int
    {

        $lastGoal = $this->goals->last();
        // Last goal always exists because the game is never tied but this is for safety
        if (!$lastGoal instanceof Goal) {
            return 3600;
        }
        // If the last goal was scored in regular time
        if ($lastGoal->getMinutes() < 60) {
            return 3600;
        }
        // If the last goal was scored in over-time then the game ended after it was scored
        return $lastGoal->getMinutes() * 60 + $lastGoal->getSeconds();
    }
}
