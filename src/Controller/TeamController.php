<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TeamRepository;
use App\Service\PlayerStatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public function __construct(
        private readonly PlayerStatisticsService $playerStatisticsService,
        private readonly TeamRepository $teamRepository,
    ) {
    }

    #[Route('/teams', name: 'teams')]
    public function index(): Response
    {
        $teams = $this->teamRepository->findAll();
        $statistics = [];
        $goalies = [];
        foreach ($teams as $team) {
            $statistics[$team->getId()] = $this->playerStatisticsService->getPlayerStatsForTeam($team);
            $goalies[$team->getId()] = $this->playerStatisticsService->getGoalieStatsForTeam($team);
        }

        return $this->render(
            'teams.html.twig',
            [
                'teams' => $teams,
                'playerStats' => $statistics,
                'goalieStats' => $goalies,
            ]
        );
    }
}
