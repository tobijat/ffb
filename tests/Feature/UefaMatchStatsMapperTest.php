<?php

namespace Tests\Feature;

use App\Services\UefaMatchStatsMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UefaMatchStatsMapperTest extends TestCase
{
    #[Test]
    public function maps_score_lineups_goals_cards_subs_and_penalty_shootout(): void
    {
        $match = [
            'id' => '99',
            'homeTeam' => ['id' => '10'],
            'awayTeam' => ['id' => '20'],
            'score' => [
                'regular' => ['home' => 1, 'away' => 1],
                'penalty' => ['home' => 5, 'away' => 3],
                'total' => ['home' => 1, 'away' => 1],
            ],
            'winner' => ['match' => ['reason' => 'WIN_ON_PENALTIES']],
            'playerEvents' => [
                'scorers' => [
                    [
                        'id' => 'g1',
                        'goalType' => 'SCORED',
                        'player' => ['id' => '100'],
                        'time' => ['minute' => 12],
                        'phase' => 'FIRST_HALF',
                    ],
                    [
                        'id' => 'g2',
                        'goalType' => 'OWN',
                        'player' => ['id' => '200'],
                        'time' => ['minute' => 40],
                        'phase' => 'FIRST_HALF',
                    ],
                ],
                'penaltyScorers' => [
                    ['player' => ['id' => '101'], 'teamId' => '10', 'penaltyType' => 'SCORED', 'phase' => 'PENALTY'],
                    ['player' => ['id' => '201'], 'teamId' => '20', 'penaltyType' => 'MISSED', 'phase' => 'PENALTY'],
                    ['player' => ['id' => '201'], 'teamId' => '20', 'penaltyType' => 'SCORED', 'phase' => 'PENALTY'],
                ],
            ],
        ];

        $lineups = [
            'homeTeam' => [
                'team' => ['id' => '10'],
                'field' => [
                    ['player' => [
                        'id' => '100',
                        'internationalName' => 'Home Striker',
                        'translations' => [
                            'firstName' => ['DE' => 'Home'],
                            'lastName' => ['DE' => 'Striker'],
                        ],
                    ]],
                    ['player' => [
                        'id' => '101',
                        'internationalName' => 'Home Mid',
                        'translations' => [
                            'firstName' => ['DE' => 'Home'],
                            'lastName' => ['DE' => 'Mid'],
                        ],
                    ]],
                ],
                'bench' => [
                    ['player' => [
                        'id' => '102',
                        'internationalName' => 'Home Sub',
                        'translations' => [
                            'firstName' => ['DE' => 'Home'],
                            'lastName' => ['DE' => 'Sub'],
                        ],
                    ]],
                    ['player' => [
                        'id' => '199',
                        'internationalName' => 'Unused',
                        'translations' => [
                            'firstName' => ['DE' => 'Unused'],
                            'lastName' => ['DE' => 'Bench'],
                        ],
                    ]],
                ],
            ],
            'awayTeam' => [
                'team' => ['id' => '20'],
                'field' => [
                    ['player' => [
                        'id' => '200',
                        'internationalName' => 'Away Defender',
                        'translations' => [
                            'firstName' => ['DE' => 'Away'],
                            'lastName' => ['DE' => 'Defender'],
                        ],
                    ]],
                    ['player' => [
                        'id' => '201',
                        'internationalName' => 'Away Forward',
                        'translations' => [
                            'firstName' => ['DE' => 'Away'],
                            'lastName' => ['DE' => 'Forward'],
                        ],
                    ]],
                ],
                'bench' => [],
            ],
        ];

        $events = [
            [
                'id' => 'g1',
                'type' => 'GOAL',
                'phase' => 'FIRST_HALF',
                'time' => ['minute' => 12],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '100'], 'team' => ['id' => '10']],
                'secondaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '101'], 'team' => ['id' => '10']],
            ],
            [
                'id' => 'g2',
                'type' => 'GOAL',
                'phase' => 'FIRST_HALF',
                'time' => ['minute' => 40],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '200'], 'team' => ['id' => '20']],
            ],
            [
                'type' => 'YELLOW_CARD',
                'phase' => 'FIRST_HALF',
                'time' => ['minute' => 30],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '200'], 'team' => ['id' => '20']],
            ],
            [
                'type' => 'RED_CARD',
                'phase' => 'SECOND_HALF',
                'time' => ['minute' => 70],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '200'], 'team' => ['id' => '20']],
            ],
            [
                'type' => 'SUBSTITUTION',
                'phase' => 'SECOND_HALF',
                'time' => ['minute' => 60],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '100'], 'team' => ['id' => '10']],
                'secondaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '102'], 'team' => ['id' => '10']],
            ],
        ];

        $playerStatistics = [
            [
                'playerId' => '101',
                'teamId' => '10',
                'statistics' => [
                    ['name' => 'assists', 'value' => '1'],
                    ['name' => 'goals', 'value' => '0'],
                ],
            ],
            [
                'playerId' => '100',
                'teamId' => '10',
                'statistics' => [
                    ['name' => 'assists', 'value' => '0'],
                    ['name' => 'goals', 'value' => '1'],
                ],
            ],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, $events, $playerStatistics);

        $this->assertSame(120, $mapped['match_minutes']);
        $this->assertSame([
            'homescore' => 1,
            'guestscore' => 1,
            'homescore_penalty' => 5,
            'guestscore_penalty' => 3,
        ], $mapped['result']);

        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_uefa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_uefa_id']] = $row;
        }

        $this->assertArrayHasKey('100', $homeById);
        $this->assertArrayHasKey('102', $homeById);
        $this->assertArrayNotHasKey('199', $homeById);

        $this->assertSame(1, $homeById['100']['player_num_goals']);
        $this->assertSame('12', $homeById['100']['player_goal']);
        $this->assertSame(1, $homeById['100']['player_change_in']);
        $this->assertSame(60, $homeById['100']['player_change_out']);
        $this->assertSame(60, $homeById['102']['player_change_in']);
        $this->assertSame(1, $homeById['101']['player_num_assists']);
        $this->assertSame(0, $homeById['100']['player_num_assists']);
        $this->assertSame(1, $homeById['101']['player_penalties_hit']);

        $this->assertSame('40', $guestById['200']['player_owngoal']);
        $this->assertSame('YR', $guestById['200']['player_cards']);
        $this->assertSame(1, $guestById['200']['player_change_in']);
        $this->assertSame(70, $guestById['200']['player_change_out']);
        $this->assertSame(70, $guestById['200']['player_minutes']);
        $this->assertSame(1, $guestById['201']['player_penalties_fail']);
        $this->assertSame(1, $guestById['201']['player_penalties_hit']);
    }

    #[Test]
    public function ignores_goal_secondary_actor_for_assists_without_matchstats(): void
    {
        $match = [
            'id' => '1',
            'homeTeam' => ['id' => '10'],
            'awayTeam' => ['id' => '20'],
            'score' => [
                'regular' => ['home' => 1, 'away' => 0],
                'total' => ['home' => 1, 'away' => 0],
            ],
            'playerEvents' => [],
        ];
        $lineups = [
            'homeTeam' => [
                'team' => ['id' => '10'],
                'field' => [
                    ['player' => [
                        'id' => '100',
                        'internationalName' => 'Scorer',
                        'translations' => [
                            'firstName' => ['DE' => 'Home'],
                            'lastName' => ['DE' => 'Scorer'],
                        ],
                    ]],
                    ['player' => [
                        'id' => '101',
                        'internationalName' => 'Keeper',
                        'translations' => [
                            'firstName' => ['DE' => 'Home'],
                            'lastName' => ['DE' => 'Keeper'],
                        ],
                    ]],
                ],
                'bench' => [],
            ],
            'awayTeam' => [
                'team' => ['id' => '20'],
                'field' => [],
                'bench' => [],
            ],
        ];
        $events = [
            [
                'type' => 'GOAL',
                'phase' => 'FIRST_HALF',
                'time' => ['minute' => 12],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '100'], 'team' => ['id' => '10']],
                // UEFA often puts the opposing/own GK here — not an assist.
                'secondaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '101'], 'team' => ['id' => '10']],
            ],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, $events);

        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_uefa_id']] = $row;
        }

        $this->assertSame(1, $homeById['100']['player_num_goals']);
        $this->assertSame(0, $homeById['101']['player_num_assists']);
    }

    #[Test]
    public function maps_assists_from_matchstats_player_statistics(): void
    {
        $match = [
            'id' => '2045230',
            'homeTeam' => ['id' => '10'],
            'awayTeam' => ['id' => '20'],
            'score' => [
                'regular' => ['home' => 2, 'away' => 0],
                'total' => ['home' => 2, 'away' => 0],
            ],
            'playerEvents' => [],
        ];
        $lineups = [
            'homeTeam' => [
                'team' => ['id' => '10'],
                'field' => [
                    ['player' => [
                        'id' => '250179341',
                        'internationalName' => 'One Assist',
                        'translations' => [
                            'firstName' => ['DE' => 'One'],
                            'lastName' => ['DE' => 'Assist'],
                        ],
                    ]],
                    ['player' => [
                        'id' => '250184619',
                        'internationalName' => 'Two Assists',
                        'translations' => [
                            'firstName' => ['DE' => 'Two'],
                            'lastName' => ['DE' => 'Assists'],
                        ],
                    ]],
                ],
                'bench' => [],
            ],
            'awayTeam' => ['team' => ['id' => '20'], 'field' => [], 'bench' => []],
        ];
        $playerStatistics = [
            [
                'playerId' => '250179341',
                'teamId' => '10',
                'statistics' => [['name' => 'assists', 'value' => '1']],
            ],
            [
                'playerId' => '250184619',
                'teamId' => '10',
                'statistics' => [['name' => 'assists', 'value' => '2']],
            ],
            [
                'playerId' => '999',
                'teamId' => '10',
                'statistics' => [['name' => 'assists', 'value' => '5']],
            ],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, [], $playerStatistics);

        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_uefa_id']] = $row;
        }

        $this->assertSame(1, $homeById['250179341']['player_num_assists']);
        $this->assertSame(2, $homeById['250184619']['player_num_assists']);
    }

    #[Test]
    public function uses_total_score_including_extra_time_goals(): void
    {
        $match = [
            'id' => '2048996',
            'homeTeam' => ['id' => '101'],
            'awayTeam' => ['id' => '39'],
            'score' => [
                'regular' => ['home' => 1, 'away' => 1],
                'total' => ['home' => 1, 'away' => 2],
            ],
            // UEFA sometimes still reports WIN_REGULAR even after ET goals.
            'winner' => ['match' => ['reason' => 'WIN_REGULAR']],
            'playerEvents' => [
                'scorers' => [
                    [
                        'goalType' => 'SCORED',
                        'player' => ['id' => '1'],
                        'phase' => 'FIRST_HALF',
                        'time' => ['minute' => 36],
                        'teamId' => '101',
                    ],
                    [
                        'goalType' => 'SCORED',
                        'player' => ['id' => '2'],
                        'phase' => 'FIRST_HALF',
                        'time' => ['minute' => 45],
                        'teamId' => '39',
                    ],
                    [
                        'goalType' => 'SCORED',
                        'player' => ['id' => '2'],
                        'phase' => 'EXTRA_TIME_FIRST_HALF',
                        'time' => ['minute' => 93],
                        'teamId' => '39',
                    ],
                ],
            ],
        ];
        $lineups = [
            'homeTeam' => ['team' => ['id' => '101'], 'field' => [], 'bench' => []],
            'awayTeam' => ['team' => ['id' => '39'], 'field' => [], 'bench' => []],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, []);

        $this->assertSame(120, $mapped['match_minutes']);
        $this->assertSame([
            'homescore' => 1,
            'guestscore' => 2,
            'homescore_penalty' => -1,
            'guestscore_penalty' => -1,
        ], $mapped['result']);
    }

    #[Test]
    public function uses_regular_score_when_match_ended_after_ninety_minutes(): void
    {
        $match = [
            'id' => '1',
            'homeTeam' => ['id' => '10'],
            'awayTeam' => ['id' => '20'],
            'score' => [
                'regular' => ['home' => 2, 'away' => 1],
                // Some UEFA payloads keep total at 0:0 for 90' matches.
                'total' => ['home' => 0, 'away' => 0],
            ],
            'winner' => ['match' => ['reason' => 'WIN_REGULAR']],
            'playerEvents' => [],
        ];
        $lineups = [
            'homeTeam' => ['team' => ['id' => '10'], 'field' => [], 'bench' => []],
            'awayTeam' => ['team' => ['id' => '20'], 'field' => [], 'bench' => []],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, []);

        $this->assertSame(90, $mapped['match_minutes']);
        $this->assertSame([
            'homescore' => 2,
            'guestscore' => 1,
            'homescore_penalty' => -1,
            'guestscore_penalty' => -1,
        ], $mapped['result']);
    }

    #[Test]
    public function straight_red_card_ends_playing_time_at_dismissal_minute(): void
    {
        $match = [
            'id' => '50',
            'homeTeam' => ['id' => '10'],
            'awayTeam' => ['id' => '20'],
            'score' => [
                'regular' => ['home' => 0, 'away' => 0],
                'total' => ['home' => 0, 'away' => 0],
            ],
            'winner' => ['match' => ['reason' => 'DRAW']],
            'playerEvents' => [],
        ];
        $lineups = [
            'homeTeam' => [
                'team' => ['id' => '10'],
                'field' => [
                    ['player' => [
                        'id' => '300',
                        'internationalName' => 'Hot Head',
                        'translations' => [
                            'firstName' => ['DE' => 'Hot'],
                            'lastName' => ['DE' => 'Head'],
                        ],
                    ]],
                ],
                'bench' => [],
            ],
            'awayTeam' => [
                'team' => ['id' => '20'],
                'field' => [],
                'bench' => [],
            ],
        ];
        $events = [
            [
                'type' => 'RED_CARD',
                'phase' => 'FIRST_HALF',
                'time' => ['minute' => 23],
                'primaryActor' => ['type' => 'PLAYER', 'person' => ['id' => '300'], 'team' => ['id' => '10']],
            ],
        ];

        $mapped = (new UefaMatchStatsMapper)->map($match, $lineups, $events);
        $player = $mapped['home'][0];

        $this->assertSame('R', $player['player_cards']);
        $this->assertSame(1, $player['player_change_in']);
        $this->assertSame(23, $player['player_change_out']);
        $this->assertSame(23, $player['player_minutes']);
    }
}
