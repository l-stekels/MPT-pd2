<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RefereeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefereeRepository::class)]
class Referee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\OneToMany(mappedBy: 'mainReferee', targetEntity: Game::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $mainGames;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'assistantReferees', cascade: ['persist'])]
    private Collection $assistedGames;

    public function __construct(
        string $firstName,
        string $lastName,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mainGames = new ArrayCollection();
        $this->assistedGames = new ArrayCollection();
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

    /**
     * @return Collection<int, Game>
     */
    public function getMainGames(): Collection
    {
        return $this->mainGames;
    }

    public function addMainGame(Game $mainGame): void
    {
        if (!$this->mainGames->contains($mainGame)) {
            $this->mainGames->add($mainGame);
        }
    }

    /**
     * @return Collection<int, Game>
     */
    public function getAssistedGames(): Collection
    {
        return $this->assistedGames;
    }

    public function addAssistedGame(Game $assistedGame): void
    {
        if (!$this->assistedGames->contains($assistedGame)) {
            $this->assistedGames->add($assistedGame);
        }
    }
}
