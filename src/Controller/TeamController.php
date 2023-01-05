<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepo,
        private readonly TeamRepository $teamRepository,
    )
    {
    }

    #[Route('/teams', name: 'teams')]
    public function index(): Response
    {
        $teams = $this->teamRepository->findAll();
        $statistics = [];
        foreach ($teams as $team) {
            $statistics[$team->getId()] = $this->playerRepo->getPlayerStatisticsForTeam($team);
        }

        return $this->render(
            'teams.html.twig',
            [
                'teams' => $teams,
                'playerStats' => $statistics,
            ]
        );
    }
}
