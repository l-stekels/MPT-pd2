<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\PlayerRepository;
use App\Repository\StatisticsRepository;
use App\Service\FileService;
use App\Tests\BaseIntegrationTestCase;

class FileServiceTest extends BaseIntegrationTestCase
{
    private FileService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = self::getContainer()->get(FileService::class);
    }

    public function test_first_round(): void
    {
        $files = [
            'futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols0.json'),
            'futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols1.json'),
            'futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols2.json'),
        ];

        $this->service->process($files);

        $statistics = self::getContainer()->get(StatisticsRepository::class)->getTeamStatistics();
        $team = $statistics[0];
        self::assertSame('Skolmeistari', $team->name);
        self::assertSame(8, $team->points);
        self::assertSame(1, $team->onTimeWins);
        self::assertSame(0, $team->onTimeLoses);
        self::assertSame(1, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(5, $team->goalsScored);
        self::assertSame(2, $team->goalsLost);
        $team = $statistics[1];
        self::assertSame('Veiklie', $team->name);
        self::assertSame(6, $team->points);
        self::assertSame(1, $team->onTimeWins);
        self::assertSame(1, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(3, $team->goalsScored);
        self::assertSame(4, $team->goalsLost);
        $team = $statistics[2];
        self::assertSame('Barcelona', $team->name);
        self::assertSame(3, $team->points);
        self::assertSame(0, $team->onTimeWins);
        self::assertSame(1, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(1, $team->overTimeLoses);
        self::assertSame(4, $team->goalsScored);
        self::assertSame(6, $team->goalsLost);
    }

    public function test_second_round(): void
    {
        $files = [
            'futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols0.json'),
            'futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols1.json'),
            'futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols2.json'),
        ];

        $this->service->process($files);

        $statistics = self::getContainer()->get(StatisticsRepository::class)->getTeamStatistics();
        $team = $statistics[0];
        self::assertSame('Veiklie', $team->name);
        self::assertSame(10, $team->points);
        self::assertSame(2, $team->onTimeWins);
        self::assertSame(0, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(5, $team->goalsScored);
        self::assertSame(3, $team->goalsLost);
        $team = $statistics[1];
        self::assertSame('Barcelona', $team->name);
        self::assertSame(6, $team->points);
        self::assertSame(1, $team->onTimeWins);
        self::assertSame(1, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(2, $team->goalsScored);
        self::assertSame(2, $team->goalsLost);
        $team = $statistics[2];
        self::assertSame('Skolmeistari', $team->name);
        self::assertSame(2, $team->points);
        self::assertSame(0, $team->onTimeWins);
        self::assertSame(2, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(2, $team->goalsScored);
        self::assertSame(4, $team->goalsLost);
    }

    public function test_both_rounds(): void
    {
        $files = [
            '0futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols0.json'),
            '0futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols1.json'),
            '0futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols2.json'),
            '1futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols0.json'),
            '1futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols1.json'),
            '1futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols2.json'),
        ];

        $this->service->process($files);

        $statistics = self::getContainer()->get(StatisticsRepository::class)->getTeamStatistics();
        $team = $statistics[0];
        self::assertSame('Veiklie', $team->name);
        self::assertSame(16, $team->points);
        self::assertSame(3, $team->onTimeWins);
        self::assertSame(1, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(8, $team->goalsScored);
        self::assertSame(7, $team->goalsLost);
        $team = $statistics[1];
        self::assertSame('Skolmeistari', $team->name);
        self::assertSame(10, $team->points);
        self::assertSame(1, $team->onTimeWins);
        self::assertSame(2, $team->onTimeLoses);
        self::assertSame(1, $team->overTimeWins);
        self::assertSame(0, $team->overTimeLoses);
        self::assertSame(7, $team->goalsScored);
        self::assertSame(6, $team->goalsLost);
        $team = $statistics[2];
        self::assertSame('Barcelona', $team->name);
        self::assertSame(9, $team->points);
        self::assertSame(1, $team->onTimeWins);
        self::assertSame(2, $team->onTimeLoses);
        self::assertSame(0, $team->overTimeWins);
        self::assertSame(1, $team->overTimeLoses);
        self::assertSame(6, $team->goalsScored);
        self::assertSame(8, $team->goalsLost);
    }

    public function test_both_rounds_player_statistics(): void
    {
        $files = [
            '0futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols0.json'),
            '0futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols1.json'),
            '0futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONFirstRound/futbols2.json'),
            '1futbols0.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols0.json'),
            '1futbols1.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols1.json'),
            '1futbols2.json' => \file_get_contents(dirname(__DIR__).'/data/JSONSecondRound/futbols2.json'),
        ];

        $this->service->process($files);

        $statistics = self::getContainer()->get(PlayerRepository::class)->getPlayerStatistics();

        self::assertCount(45, $statistics);
        self::assertSame('Marco', $statistics[0]->firstName);
        self::assertSame('Verratti', $statistics[0]->lastName);
        self::assertSame(24, $statistics[0]->number);
        self::assertSame('Barcelona', $statistics[0]->team);
        self::assertSame(2, $statistics[0]->goals);
        self::assertSame(1, $statistics[0]->assists);
        self::assertSame('Linards', $statistics[4]->firstName);
        self::assertSame('Grants', $statistics[4]->lastName);
        self::assertSame(34, $statistics[4]->number);
        self::assertSame('Skolmeistari', $statistics[4]->team);
        self::assertSame(1, $statistics[4]->goals);
        self::assertSame(2, $statistics[4]->assists);
        self::assertSame('Rolands', $statistics[26]->firstName);
        self::assertSame('Kompass', $statistics[26]->lastName);
        self::assertSame(44, $statistics[26]->number);
        self::assertSame('Skolmeistari', $statistics[26]->team);
        self::assertSame(0, $statistics[26]->goals);
        self::assertSame(1, $statistics[26]->assists);
    }
}
