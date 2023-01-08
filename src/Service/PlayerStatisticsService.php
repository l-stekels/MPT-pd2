<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Enum\PlayerRole;
use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;

class PlayerStatisticsService
{
    public function __construct(
        private readonly PlayerRepository $playerRepo,
    ) {
    }

    public function getGoalieStatsForTeam(Team $team): array
    {
        $goalies = $this->playerRepo->findByTeam($team, [PlayerRole::GOALIE]);

        return \array_map(fn (Player $player) => [
            'number' => $player->getNumber(),
            'firstName' => $player->getFirstName(),
            'lastName' => $player->getLastName(),
            'gamesPlayed' => $player->getGames()->count(),
            ...$this->getGoalStatistics($player),
        ], $goalies);
    }

    public function getPlayerStatsForTeam(Team $team): array
    {
        $players = $this->playerRepo->findByTeam($team, [PlayerRole::DEFENDER, PlayerRole::FORWARD]);

        return \array_map(fn (Player $player) => [
            'number' => $player->getNumber(),
            'firstName' => $player->getFirstName(),
            'lastName' => $player->getLastName(),
            'gamesPlayed' => $player->getGames()->count() + $player->getGamesSubstituted()->count(),
            'coreGamesPlayed' => $player->getGames()->count(),
            'goals' => $player->getGoals()->count(),
            'assists' => $player->getAssists()->count(),
            ...$this->calculateMinutesPlayedInTime($player),
            ...$this->calculatePenalties($player),
        ], $players);
    }

    private function calculateMinutesPlayedInTime(Player $player): array
    {
        $timePlayed = 0;
        // Ja mums ir informācija šeit, tas nozīmē, ka spēlētājs bija pamatsastāvā šajā spēlē
        foreach ($player->getGames() as $game) {
            foreach ($game->getSubstitutions() as $substitution) {
                if ($substitution->getPlayerSubstituted()->getId() === $player->getId()) {
                    $timePlayed += $substitution->getMinutes() * 60 + $substitution->getSeconds();
                    continue 2;
                }
            }
            $timePlayed += $game->getTotalGameTime();
        }
        $gamesNotPlayed = [];
        foreach ($player->getTeam()->getGames() as $game) {
            if (!$player->getGames()->contains($game)) {
                $gamesNotPlayed[] = $game;
            }
        }
        if ([] !== $gamesNotPlayed) {
            foreach ($gamesNotPlayed as $game) {
                foreach ($game->getSubstitutions() as $substitution) {
                    if ($substitution->getNewPlayer()->getId() === $player->getId()) {
                        $timePlayed += $game->getTotalGameTime() - ($substitution->getMinutes(
                                ) * 60 + $substitution->getSeconds());
                    }
                }
            }
        }

        return [
            'minutes' => (int) ($timePlayed / 60),
            'seconds' => $timePlayed % 60,
        ];
    }

    private function calculatePenalties(Player $player): array
    {
        $red = 0;
        $yellow = 0;
        $gameId = 0;
        foreach ($player->getPenalties() as $penalty) {
            if ($gameId === $penalty->getGame()->getId()) {
                $red++;
                $yellow--;
            } else {
                $yellow++;
            }
            $gameId = $penalty->getGame()->getId();
        }

        return [
            'yellow' => $yellow,
            'red' => $red,
        ];
    }

    private function getGoalStatistics(Player $player): array
    {
        $goalsAgainst = 0;
        foreach ($player->getGames() as $game) {
            foreach ($game->getGoals() as $goal) {
                if ($goal->getTeam()->getId() === $player->getTeam()->getId()) {
                    ++$goalsAgainst;
                }
            }
        }

        return [
            'goalsAgainst' => $goalsAgainst,
            'averageGoalsAgainst' => number_format($goalsAgainst / $player->getGames()->count(), 1),
        ];
    }
}
