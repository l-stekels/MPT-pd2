<?php

declare(strict_types=1);

namespace App\Tests\Service;

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
        self::assertSame('Skolmeistari', $team['name']);
        self::assertSame(8, $team['points']);
        self::assertSame(1, $team['on_time_wins']);
        self::assertSame(0, $team['on_time_loses']);
        self::assertSame(1, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(5, $team['goals_scored']);
        self::assertSame(2, $team['goals_lost']);
        $team = $statistics[1];
        self::assertSame('Veiklie', $team['name']);
        self::assertSame(6, $team['points']);
        self::assertSame(1, $team['on_time_wins']);
        self::assertSame(1, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(3, $team['goals_scored']);
        self::assertSame(4, $team['goals_lost']);
        $team = $statistics[2];
        self::assertSame('Barcelona', $team['name']);
        self::assertSame(3, $team['points']);
        self::assertSame(0, $team['on_time_wins']);
        self::assertSame(1, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(1, $team['overtime_loses']);
        self::assertSame(4, $team['goals_scored']);
        self::assertSame(6, $team['goals_lost']);
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
        self::assertSame('Veiklie', $team['name']);
        self::assertSame(10, $team['points']);
        self::assertSame(2, $team['on_time_wins']);
        self::assertSame(0, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(5, $team['goals_scored']);
        self::assertSame(3, $team['goals_lost']);
        $team = $statistics[1];
        self::assertSame('Barcelona', $team['name']);
        self::assertSame(6, $team['points']);
        self::assertSame(1, $team['on_time_wins']);
        self::assertSame(1, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(2, $team['goals_scored']);
        self::assertSame(2, $team['goals_lost']);
        $team = $statistics[2];
        self::assertSame('Skolmeistari', $team['name']);
        self::assertSame(2, $team['points']);
        self::assertSame(0, $team['on_time_wins']);
        self::assertSame(2, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(2, $team['goals_scored']);
        self::assertSame(4, $team['goals_lost']);
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
        self::assertSame('Veiklie', $team['name']);
        self::assertSame(16, $team['points']);
        self::assertSame(3, $team['on_time_wins']);
        self::assertSame(1, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(8, $team['goals_scored']);
        self::assertSame(7, $team['goals_lost']);
        $team = $statistics[1];
        self::assertSame('Skolmeistari', $team['name']);
        self::assertSame(10, $team['points']);
        self::assertSame(1, $team['on_time_wins']);
        self::assertSame(2, $team['on_time_loses']);
        self::assertSame(1, $team['overtime_wins']);
        self::assertSame(0, $team['overtime_loses']);
        self::assertSame(7, $team['goals_scored']);
        self::assertSame(6, $team['goals_lost']);
        $team = $statistics[2];
        self::assertSame('Barcelona', $team['name']);
        self::assertSame(9, $team['points']);
        self::assertSame(1, $team['on_time_wins']);
        self::assertSame(2, $team['on_time_loses']);
        self::assertSame(0, $team['overtime_wins']);
        self::assertSame(1, $team['overtime_loses']);
        self::assertSame(6, $team['goals_scored']);
        self::assertSame(8, $team['goals_lost']);
    }
}
