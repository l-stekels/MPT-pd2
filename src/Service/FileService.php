<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO;
use App\Entity\Enum\PlayerRole;
use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\Penalty;
use App\Entity\Player;
use App\Entity\Referee;
use App\Entity\Statistics;
use App\Entity\Substitution;
use App\Entity\Team;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\RefereeRepository;
use App\Repository\TeamRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileService
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly GameRepository $gameRepo,
        private readonly RefereeRepository $refereeRepo,
        private readonly TeamRepository $teamRepo,
        private readonly PlayerRepository $playerRepo,
    ) {
    }

    /**
     * @param array<string, string> $files
     */
    public function process(array $files): array
    {
        $errorsPerFile = [];
        // Can return an array of possible errors that were received during file processing
        foreach ($files as $name => $jsonString) {
            $data = \json_decode($jsonString, true, \JSON_THROW_ON_ERROR);
            $game = DTO\Game::createFromArray($data);
            // First level of validation check if the json has all the required data
            $errors = $this->validator->validate($game);
            if ($errors->count() > 0) {
                $errorsPerFile[$name] = (array) $errors;
                continue;
            }
            // Second level of validation check if the data in this json file is not duplicate
            $consistencyErrors = $this->validateDataConsistency($game);
            if ([] !== $consistencyErrors) {
                $errorsPerFile[$name] = $consistencyErrors;
                continue;
            }
            $gameEntity = $this->persistGameData($game);
            $this->createGameStatistics($gameEntity);
        }

        return $errorsPerFile;
    }

    private function validateDataConsistency(DTO\Game $game): array
    {
        $teamNames = \array_map(static fn (DTO\Team $team) => $team->name, $game->teams);
        $games = $this->gameRepo->findBy([
            'date' => $game->date,
        ]);
        foreach ($games as $gameEntity) {
            $teamsPlaying = $gameEntity->getTeams()->map(static fn (Team $team) => $team->getName())->toArray();
            // Iegūst jaunu masīvu ar visiem tiem elementiem, kas ir atrodami abos dotajos masīvos
            $intersection = \array_intersect($teamNames, $teamsPlaying);
            if ([] === $intersection) {
                continue;
            }

            return [
                \sprintf(
                    'Komandas %s jau ir spēlējušas šajā dienā!',
                    \implode(', ', $teamNames)
                ),
            ];
        }

        return [];
    }

    private function persistGameData(DTO\Game $game): Game
    {
        $gameEntity = new Game(
            $game->date,
            $game->viewers,
            $game->stadium,
        );
        foreach ($game->referees as $referee) {
            $refereeEntity = $this->getRefereeEntity($referee);
            if ($referee->isMain) {
                $gameEntity->setMainReferee($refereeEntity);
            } else {
                $gameEntity->addAssistantReferee($refereeEntity);
            }
        }
        foreach ($game->teams as $team) {
            $teamEntity = $this->getTeamEntity($team);
            $gameEntity->addTeam($teamEntity);
            $this->createPenalties($team, $gameEntity);
            $this->addPlayers($team, $gameEntity);
            $this->createGoals($team, $gameEntity);
            $this->createSubstitutions($team, $gameEntity);
        }
        $this->gameRepo->persist($gameEntity);
        $this->gameRepo->flush();

        return $gameEntity;
    }

    private function getRefereeEntity(DTO\Referee $referee): Referee
    {
        $refereeEntity = $this->refereeRepo->findOneBy([
            'firstName' => $referee->firstName,
            'lastName' => $referee->lastName,
        ]);
        if (null === $refereeEntity) {
            $refereeEntity = new Referee(
                $referee->firstName,
                $referee->lastName,
            );
        }

        return $refereeEntity;
    }

    private function getTeamEntity(DTO\Team $team): Team
    {
        $teamEntity = $this->teamRepo->findOneBy([
            'name' => $team->name,
        ]);
        if (null !== $teamEntity) {
            return $teamEntity;
        }
        $teamEntity = new Team(
            $team->name,
        );
        foreach ($team->players as $player) {
            new Player(
                $player->firstName,
                $player->lastName,
                PlayerRole::tryFrom($player->role),
                $player->number,
                $teamEntity,
            );
        }
        $this->teamRepo->persist($teamEntity);
        $this->teamRepo->flush();

        return $teamEntity;
    }

    private function createPenalties(DTO\Team $team, Game $game): void
    {
        // Pārbaudīt vai katram spēlētājam ir ne vairāk kā divi pārkāpumi?
        foreach ($team->penalties as $penalty) {
            $player = $this->playerRepo->findOneByTeamNameAndNumber($team->name, $penalty->player);
            $time = \explode(':', $penalty->time);
            new Penalty(
                $player,
                (int) $time[0],
                (int) $time[1],
                $game,
            );
        }
    }

    private function addPlayers(DTO\Team $team, Game $game): void
    {
        $players = $this->playerRepo->findByTeamNameAndNumbers($team->name, $team->playersOnFieldAtStart);
        foreach ($players as $player) {
            $game->addPlayer($player);
        }
    }

    private function createGoals(DTO\Team $team, Game $game): void
    {
        foreach ($team->goals as $goal) {
            $player = $this->playerRepo->findOneByTeamNameAndNumber($team->name, $goal->scorer);
            $assistants = $this->playerRepo->findByTeamNameAndNumbers(
                $team->name,
                $goal->assists,
            );
            $time = \explode(':', $goal->time);
            new Goal(
                $player,
                $assistants,
                (int) $time[0],
                (int) $time[1],
                $goal->isPenaltyKick,
                $game
            );
        }
    }

    private function createSubstitutions(DTO\Team $team, Game $game): void
    {
        foreach ($team->substitutes as $substitute) {
            $playerSubstituted = $this->playerRepo->findOneByTeamNameAndNumber(
                $team->name,
                $substitute->from,
            );
            $newPlayer = $this->playerRepo->findOneByTeamNameAndNumber(
                $team->name,
                $substitute->to,
            );
            $time = \explode(':', $substitute->time);
            new Substitution(
                $playerSubstituted,
                $newPlayer,
                (int) $time[0],
                (int) $time[1],
                $game,
            );
        }
    }

    private function createGameStatistics(Game $game): void
    {
        $this->gameRepo->refresh($game);
        $firstTeam = $game->getTeams()->first();
        $secondTeam = $game->getTeams()->last();
        $results = [
            $firstTeam->getId() => ['team' => $firstTeam, 'score' => 0, 'overtime' => 0, 'ontime' => 0],
            $secondTeam->getId() => ['team' => $secondTeam, 'score' => 0, 'overtime' => 0, 'ontime' => 0],
        ];
        $overtime = false;
        foreach ($game->getGoals() as $goal) {
            ++$results[$goal->getPlayer()->getTeam()->getId()]['score'];
            if ($goal->getMinutes() < 60) {
                ++$results[$goal->getPlayer()->getTeam()->getId()]['ontime'];
            } else {
                $overtime = true;
                ++$results[$goal->getPlayer()->getTeam()->getId()]['overtime'];
            }
        }
        \usort($results, static fn (array $a, array $b) => $b['score'] <=> $a['score']);
        $this->gameRepo->persist(
            new Statistics(
                $results[0]['team'],
                $results[0]['ontime'],
                $results[0]['overtime'],
                $results[1]['ontime'],
                $results[1]['overtime'],
                $overtime ? 3 : 5,
                true,
                $overtime
            )
        );
        $this->gameRepo->persist(
            new Statistics(
                $results[1]['team'],
                $results[1]['ontime'],
                $results[1]['overtime'],
                $results[0]['ontime'],
                $results[0]['overtime'],
                $overtime ? 2 : 1,
                false,
                $overtime,
            )
        );
        $this->gameRepo->flush();
    }
}
