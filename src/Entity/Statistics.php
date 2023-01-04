<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StatisticsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticsRepository::class)]
class Statistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $team;

    #[ORM\Column]
    private int $goalsInTime;

    #[ORM\Column]
    private int $goalsInOvertime;

    #[ORM\Column]
    private int $goalsLostInTime;

    #[ORM\Column]
    private int $goalsLostInOvertime;

    #[ORM\Column]
    private int $points;

    #[ORM\Column]
    private bool $win;

    #[ORM\Column]
    private bool $overtime;

    public function __construct(
        Team $team,
        int $goalsInTime,
        int $goalsInOvertime,
        int $goalsLostInTime,
        int $goalsLostInOvertime,
        int $points,
        bool $win,
        bool $overtime,
    ) {
        $this->team = $team;
        $team->addStatistic($this);
        $this->goalsInTime = $goalsInTime;
        $this->goalsInOvertime = $goalsInOvertime;
        $this->goalsLostInTime = $goalsLostInTime;
        $this->goalsLostInOvertime = $goalsLostInOvertime;
        $this->points = $points;
        $this->win = $win;
        $this->overtime = $overtime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getGoalsInTime(): int
    {
        return $this->goalsInTime;
    }

    public function getGoalsInOvertime(): int
    {
        return $this->goalsInOvertime;
    }

    public function getGoals(): int
    {
        return $this->goalsInTime + $this->goalsInOvertime;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function isWin(): bool
    {
        return $this->win;
    }

    public function isOvertime(): bool
    {
        return $this->overtime;
    }

    public function getGoalsLostInTime(): int
    {
        return $this->goalsLostInTime;
    }

    public function getGoalsLostInOvertime(): int
    {
        return $this->goalsLostInOvertime;
    }
}
