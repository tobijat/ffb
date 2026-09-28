<?php

namespace Tests\Feature;

use App\Services\FifaMatchStatsMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FifaMatchStatsMapperTest extends TestCase
{
    #[Test]
    public function maps_score_lineups_goals_cards_subs_owngoals_and_penalty_shootout(): void
    {
        $live = [
            'IdMatch' => '4001',
            'ResultType' => 2,
            'HomeTeamPenaltyScore' => 5,
            'AwayTeamPenaltyScore' => 3,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 1,
                'Players' => [
                    $this->player('100', 'Home Striker', 1),
                    $this->player('101', 'Home Mid', 1),
                    $this->player('102', 'Home Sub', 2),
                    $this->player('199', 'Unused Bench', 2),
                ],
                'Goals' => [
                    [
                        'Type' => 2,
                        'IdPlayer' => '100',
                        'Minute' => "12'",
                        'IdAssistPlayer' => '101',
                        'Period' => 3,
                        'IdTeam' => '10',
                    ],
                    // Own goal by away player listed on home (benefiting) side.
                    [
                        'Type' => 3,
                        'IdPlayer' => '200',
                        'Minute' => "40'",
                        'IdAssistPlayer' => '',
                        'Period' => 3,
                        'IdTeam' => '20',
                    ],
                    [
                        'Type' => 1,
                        'IdPlayer' => '101',
                        'Minute' => "121'",
                        'IdAssistPlayer' => '100',
                        'Period' => 11,
                        'IdTeam' => '10',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [
                    [
                        'IdPlayerOff' => '100',
                        'IdPlayerOn' => '102',
                        'Minute' => "60'",
                        'Period' => 5,
                        'IdTeam' => '10',
                    ],
                ],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 1,
                'Players' => [
                    $this->player('200', 'Away Defender', 1),
                    $this->player('201', 'Away Forward', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 1,
                        'IdPlayer' => '201',
                        'Minute' => "122'",
                        'IdAssistPlayer' => '',
                        'Period' => 11,
                        'IdTeam' => '20',
                    ],
                ],
                'Bookings' => [
                    [
                        'Card' => 1,
                        'IdPlayer' => '200',
                        'Minute' => "30'",
                        'Period' => 3,
                        'IdTeam' => '20',
                    ],
                    [
                        'Card' => 3,
                        'IdPlayer' => '200',
                        'Minute' => "70'",
                        'Period' => 5,
                        'IdTeam' => '20',
                    ],
                ],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 51,
                    'Period' => 11,
                    'IdPlayer' => '201',
                    'IdTeam' => '20',
                    'MatchMinute' => "123'",
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);

        $this->assertSame(120, $mapped['match_minutes']);
        $this->assertSame('4001', $mapped['fifa_match_id']);
        $this->assertSame([
            'homescore' => 1,
            'guestscore' => 1,
            'homescore_penalty' => 5,
            'guestscore_penalty' => 3,
        ], $mapped['result']);

        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
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
        $this->assertSame(1, $homeById['101']['player_penalties_hit']);
        $this->assertSame(0, $homeById['101']['player_num_goals']);

        $this->assertSame('40', $guestById['200']['player_owngoal']);
        $this->assertSame('YR', $guestById['200']['player_cards']);
        $this->assertSame(1, $guestById['200']['player_change_in']);
        $this->assertSame(70, $guestById['200']['player_change_out']);
        $this->assertSame(70, $guestById['200']['player_minutes']);
        $this->assertSame(1, $guestById['201']['player_penalties_hit']);
        $this->assertSame(1, $guestById['201']['player_penalties_fail']);
    }

    #[Test]
    public function maps_penalty_shootout_saves_to_goalkeeper_from_type60(): void
    {
        // SUI–COL pattern: Type 60 IdPlayer = taker, IdSubPlayer = opposing GK.
        $live = [
            'IdMatch' => 'ps-saves',
            'ResultType' => 2,
            'HomeTeamPenaltyScore' => 4,
            'AwayTeamPenaltyScore' => 3,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 1,
                'Players' => [
                    $this->player('1', 'Home GK', 1),
                    $this->player('2', 'Home Taker Miss', 1),
                    $this->player('3', 'Home Taker Hit', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 1,
                        'IdPlayer' => '3',
                        'Minute' => "121'",
                        'IdAssistPlayer' => '',
                        'Period' => 11,
                        'IdTeam' => '10',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 1,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                    $this->player('22', 'Away Taker Miss', 1),
                    $this->player('23', 'Away Taker Hit', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 1,
                        'IdPlayer' => '23',
                        'Minute' => "122'",
                        'IdAssistPlayer' => '',
                        'Period' => 11,
                        'IdTeam' => '20',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 41,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '23',
                    'IdSubPlayer' => '1',
                    'IdTeam' => '20',
                    'IdSubTeam' => '10',
                ],
                [
                    'Type' => 60,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '22',
                    'IdSubPlayer' => '1',
                    'IdTeam' => '20',
                    'IdSubTeam' => '10',
                ],
                [
                    'Type' => 41,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '3',
                    'IdSubPlayer' => '21',
                    'IdTeam' => '10',
                    'IdSubTeam' => '20',
                ],
                [
                    'Type' => 60,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '2',
                    'IdSubPlayer' => '21',
                    'IdTeam' => '10',
                    'IdSubTeam' => '20',
                ],
                [
                    'Type' => 60,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '22',
                    'IdSubPlayer' => '1',
                    'IdTeam' => '20',
                    'IdSubTeam' => '10',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['3']['player_penalties_hit']);
        $this->assertSame(1, $homeById['2']['player_penalties_fail']);
        $this->assertSame(0, $homeById['2']['player_penalties_shootout_save']);
        $this->assertSame(2, $homeById['1']['player_penalties_shootout_save']);

        $this->assertSame(1, $guestById['23']['player_penalties_hit']);
        $this->assertSame(2, $guestById['22']['player_penalties_fail']);
        $this->assertSame(1, $guestById['21']['player_penalties_shootout_save']);
    }

    #[Test]
    public function penalty_shootout_woodwork_and_off_target_do_not_credit_keeper_save(): void
    {
        $live = [
            'IdMatch' => 'ps-woodwork',
            'ResultType' => 2,
            'HomeTeamPenaltyScore' => 4,
            'AwayTeamPenaltyScore' => 2,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 1,
                'Players' => [
                    $this->player('1', 'Home GK', 1),
                    $this->player('2', 'Home Taker Save', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 1,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                    $this->player('22', 'Away Post', 1),
                    $this->player('23', 'Away Wide', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                // Real save — still credits the keeper.
                [
                    'Type' => 60,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '2',
                    'IdSubPlayer' => '21',
                    'IdTeam' => '10',
                    'IdSubTeam' => '20',
                    'EventDescription' => [
                        ['Locale' => 'en-GB', 'Description' => 'Home Taker Save sees his penalty saved by the goalkeeper.'],
                    ],
                ],
                // Woodwork — IdSubPlayer is still the GK, but must not count as a save.
                [
                    'Type' => 51,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '22',
                    'IdSubPlayer' => '1',
                    'IdTeam' => '20',
                    'IdSubTeam' => '10',
                    'EventDescription' => [
                        ['Locale' => 'en-GB', 'Description' => 'Away Post hits the post from the spot!'],
                    ],
                ],
                // Off target — same.
                [
                    'Type' => 65,
                    'Period' => 11,
                    'MatchMinute' => '',
                    'IdPlayer' => '23',
                    'IdSubPlayer' => '1',
                    'IdTeam' => '20',
                    'IdSubTeam' => '10',
                    'EventDescription' => [
                        ['Locale' => 'en-GB', 'Description' => 'Away Wide sees his penalty miss the target.'],
                    ],
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_penalties_fail']);
        $this->assertSame(0, $homeById['1']['player_penalties_shootout_save']);

        $this->assertSame(1, $guestById['22']['player_penalties_fail']);
        $this->assertSame(1, $guestById['23']['player_penalties_fail']);
        $this->assertSame(1, $guestById['21']['player_penalties_shootout_save']);
    }

    #[Test]
    public function maps_straight_red_bookings_separately_from_yellow_red(): void
    {
        $live = [
            'IdMatch' => 'mex-rsa',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 0,
                'Players' => [
                    $this->player('100', 'Home Yellow', 1),
                    $this->player('101', 'Home Straight Red', 1),
                    $this->player('102', 'Home Yellow Red', 1),
                ],
                'Goals' => [],
                'Bookings' => [
                    ['Card' => 1, 'IdPlayer' => '100', 'Minute' => "23'", 'Period' => 3, 'IdTeam' => '10'],
                    ['Card' => 2, 'IdPlayer' => '101', 'Minute' => "90'+2'", 'Period' => 5, 'IdTeam' => '10'],
                    ['Card' => 1, 'IdPlayer' => '102', 'Minute' => "40'", 'Period' => 3, 'IdTeam' => '10'],
                    ['Card' => 3, 'IdPlayer' => '102', 'Minute' => "70'", 'Period' => 5, 'IdTeam' => '10'],
                ],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('200', 'Away Straight Red A', 1),
                    $this->player('201', 'Away Straight Red B', 1),
                ],
                'Goals' => [],
                'Bookings' => [
                    ['Card' => 2, 'IdPlayer' => '200', 'Minute' => "49'", 'Period' => 5, 'IdTeam' => '20'],
                    ['Card' => 2, 'IdPlayer' => '201', 'Minute' => "84'", 'Period' => 5, 'IdTeam' => '20'],
                ],
                'Substitutions' => [],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame('Y', $homeById['100']['player_cards']);
        $this->assertSame('R', $homeById['101']['player_cards']);
        $this->assertSame('YR', $homeById['102']['player_cards']);
        $this->assertSame('R', $guestById['200']['player_cards']);
        $this->assertSame('R', $guestById['201']['player_cards']);
        $this->assertSame(90, $homeById['101']['player_change_out']);
        $this->assertSame(70, $homeById['102']['player_change_out']);
    }

    #[Test]
    public function uses_ninety_minutes_for_regular_time_finish(): void
    {
        $live = [
            'IdMatch' => '9',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '1',
                'Score' => 2,
                'Players' => [],
                'Goals' => [
                    ['Type' => 2, 'IdPlayer' => '1', 'Minute' => "10'", 'Period' => 3, 'IdTeam' => '1'],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '2',
                'Score' => 0,
                'Players' => [],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live);

        $this->assertSame(90, $mapped['match_minutes']);
        $this->assertSame([
            'homescore' => 2,
            'guestscore' => 0,
            'homescore_penalty' => -1,
            'guestscore_penalty' => -1,
        ], $mapped['result']);
    }

    #[Test]
    public function detects_open_play_penalty_miss_save_and_scored_from_timeline(): void
    {
        $live = [
            'IdMatch' => '55',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 1,
                'Players' => [
                    $this->player('1', 'Home GK', 1),
                    $this->player('2', 'Home Taker', 1),
                    $this->player('3', 'Home Woodwork', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 1,
                        'IdPlayer' => '2',
                        'Minute' => "20'",
                        'IdAssistPlayer' => '',
                        'Period' => 3,
                        'IdTeam' => '10',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                    $this->player('22', 'Away Taker', 1),
                    $this->player('23', 'Away Miss', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                // Scored penalty: awarded then penalty goal — no lost/saved.
                [
                    'Type' => 6,
                    'Period' => 3,
                    'MatchMinute' => "20'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                    'IdSubPlayer' => '21',
                    'IdSubTeam' => '20',
                ],
                [
                    'Type' => 41,
                    'Period' => 3,
                    'MatchMinute' => "20'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                    'IdSubPlayer' => '21',
                    'IdSubTeam' => '20',
                ],
                // Awarded then goal prevention → lost + saved.
                [
                    'Type' => 6,
                    'Period' => 5,
                    'MatchMinute' => "55'",
                    'IdPlayer' => '22',
                    'IdTeam' => '20',
                    'IdSubPlayer' => '1',
                    'IdSubTeam' => '10',
                ],
                [
                    'Type' => 57,
                    'Period' => 5,
                    'MatchMinute' => "55'",
                    'IdPlayer' => '1',
                    'IdTeam' => '10',
                ],
                // Awarded without goal → lost only.
                [
                    'Type' => 6,
                    'Period' => 5,
                    'MatchMinute' => "70'",
                    'IdPlayer' => '3',
                    'IdTeam' => '10',
                    'IdSubPlayer' => '21',
                    'IdSubTeam' => '20',
                ],
                // Explicit saved miss without preceding type 6.
                [
                    'Type' => 60,
                    'Period' => 5,
                    'MatchMinute' => "80'",
                    'IdPlayer' => '23',
                    'IdTeam' => '20',
                    'IdSubPlayer' => '1',
                    'IdSubTeam' => '10',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_num_goals']);
        $this->assertSame(0, $homeById['2']['player_penalties_lost']);
        $this->assertSame(0, $homeById['2']['player_penalties_saved']);

        $this->assertSame(1, $guestById['22']['player_penalties_lost']);
        $this->assertSame(0, $guestById['22']['player_penalties_saved']);
        $this->assertSame(2, $homeById['1']['player_penalties_saved']);

        $this->assertSame(1, $homeById['3']['player_penalties_lost']);
        $this->assertSame(0, $homeById['3']['player_penalties_saved']);
        $this->assertSame(0, $guestById['21']['player_penalties_saved']);

        $this->assertSame(1, $guestById['23']['player_penalties_lost']);
    }

    #[Test]
    public function open_play_penalty_off_target_miss_does_not_credit_goalkeeper_save(): void
    {
        $live = [
            'IdMatch' => '56',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 0,
                'Players' => [
                    $this->player('1', 'Home GK', 1),
                    $this->player('2', 'Home Taker', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 65,
                    'Period' => 5,
                    'MatchMinute' => "84'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                    'IdSubPlayer' => '21',
                    'IdSubTeam' => '20',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_penalties_lost']);
        $this->assertSame(0, $guestById['21']['player_penalties_saved']);
    }

    #[Test]
    public function open_play_penalty_wide_miss_does_not_use_later_unrelated_save(): void
    {
        // Argentina–Austria pattern: Type 6 at 9', then a normal open-play Type 57 at 19'.
        $live = [
            'IdMatch' => '57',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 0,
                'Players' => [
                    $this->player('1', 'Home GK', 1),
                    $this->player('2', 'Home Taker', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 6,
                    'Period' => 3,
                    'MatchMinute' => "9'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                    'IdSubPlayer' => null,
                    'IdSubTeam' => null,
                ],
                [
                    'Type' => 12,
                    'Period' => 3,
                    'MatchMinute' => "9'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                ],
                [
                    'Type' => 57,
                    'Period' => 3,
                    'MatchMinute' => "19'",
                    'IdPlayer' => '21',
                    'IdTeam' => '20',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_penalties_lost']);
        $this->assertSame(0, $guestById['21']['player_penalties_saved']);
        $this->assertSame(0, $homeById['1']['player_penalties_saved']);
    }

    #[Test]
    public function open_play_penalty_same_minute_goal_prevention_credits_save(): void
    {
        // Argentina–Egypt pattern: Type 6 + Type 57 share MatchMinute / IdSubPlayer.
        $live = [
            'IdMatch' => '58',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 0,
                'Players' => [
                    $this->player('2', 'Home Taker', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 6,
                    'Period' => 3,
                    'MatchMinute' => "21'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                    'IdSubPlayer' => '21',
                    'IdSubTeam' => '20',
                ],
                [
                    'Type' => 12,
                    'Period' => 3,
                    'MatchMinute' => "21'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                ],
                [
                    'Type' => 57,
                    'Period' => 3,
                    'MatchMinute' => "21'",
                    'IdPlayer' => '21',
                    'IdTeam' => '20',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_penalties_lost']);
        $this->assertSame(1, $guestById['21']['player_penalties_saved']);
    }

    #[Test]
    public function maps_assists_from_timeline_type1_then_type0(): void
    {
        $live = [
            'IdMatch' => '59',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 2,
                'Players' => [
                    $this->player('2', 'Home Assister', 1),
                    $this->player('3', 'Home Scorer', 1),
                    $this->player('4', 'Home Solo', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 2,
                        'IdPlayer' => '3',
                        'Minute' => "79'",
                        'IdAssistPlayer' => null,
                        'Period' => 5,
                        'IdTeam' => '10',
                    ],
                    [
                        'Type' => 2,
                        'IdPlayer' => '4',
                        'Minute' => "85'",
                        'IdAssistPlayer' => null,
                        'Period' => 5,
                        'IdTeam' => '10',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 1,
                    'Period' => 5,
                    'MatchMinute' => "79'",
                    'IdPlayer' => '2',
                    'IdSubPlayer' => '3',
                    'IdTeam' => '10',
                ],
                [
                    'Type' => 0,
                    'Period' => 5,
                    'MatchMinute' => "79'",
                    'IdPlayer' => '3',
                    'IdSubPlayer' => '2',
                    'IdTeam' => '10',
                ],
                // Solo goal: Type 0 without assister / Type 1.
                [
                    'Type' => 0,
                    'Period' => 5,
                    'MatchMinute' => "85'",
                    'IdPlayer' => '4',
                    'IdSubPlayer' => null,
                    'IdTeam' => '10',
                ],
                // Penalty goal: no assist.
                [
                    'Type' => 41,
                    'Period' => 5,
                    'MatchMinute' => "90'",
                    'IdPlayer' => '3',
                    'IdSubPlayer' => '21',
                    'IdTeam' => '10',
                ],
                // Own goal: Type 34 must not credit an assist.
                [
                    'Type' => 34,
                    'Period' => 5,
                    'MatchMinute' => "90'+1'",
                    'IdPlayer' => '21',
                    'IdSubPlayer' => '2',
                    'IdTeam' => '20',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }
        $guestById = [];
        foreach ($mapped['guest'] as $row) {
            $guestById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_num_assists']);
        $this->assertSame(0, $homeById['3']['player_num_assists']);
        $this->assertSame(0, $homeById['4']['player_num_assists']);
        $this->assertSame(0, $guestById['21']['player_num_assists']);
        $this->assertSame(1, $homeById['3']['player_num_goals']);
        $this->assertSame(1, $homeById['4']['player_num_goals']);
    }

    #[Test]
    public function does_not_double_count_timeline_and_live_assists(): void
    {
        $live = [
            'IdMatch' => '60',
            'ResultType' => 1,
            'HomeTeamPenaltyScore' => 0,
            'AwayTeamPenaltyScore' => 0,
            'HomeTeam' => [
                'IdTeam' => '10',
                'Score' => 1,
                'Players' => [
                    $this->player('2', 'Home Assister', 1),
                    $this->player('3', 'Home Scorer', 1),
                ],
                'Goals' => [
                    [
                        'Type' => 2,
                        'IdPlayer' => '3',
                        'Minute' => "20'",
                        'IdAssistPlayer' => '2',
                        'Period' => 3,
                        'IdTeam' => '10',
                    ],
                ],
                'Bookings' => [],
                'Substitutions' => [],
            ],
            'AwayTeam' => [
                'IdTeam' => '20',
                'Score' => 0,
                'Players' => [
                    $this->player('21', 'Away GK', 1),
                ],
                'Goals' => [],
                'Bookings' => [],
                'Substitutions' => [],
            ],
        ];

        $timeline = [
            'Event' => [
                [
                    'Type' => 1,
                    'Period' => 3,
                    'MatchMinute' => "20'",
                    'IdPlayer' => '2',
                    'IdTeam' => '10',
                ],
                [
                    'Type' => 0,
                    'Period' => 3,
                    'MatchMinute' => "20'",
                    'IdPlayer' => '3',
                    'IdSubPlayer' => '2',
                    'IdTeam' => '10',
                ],
            ],
        ];

        $mapped = (new FifaMatchStatsMapper)->map($live, $timeline);
        $homeById = [];
        foreach ($mapped['home'] as $row) {
            $homeById[$row['player_fifa_id']] = $row;
        }

        $this->assertSame(1, $homeById['2']['player_num_assists']);
    }

    /**
     * @return array<string, mixed>
     */
    private function player(string $id, string $name, int $status): array
    {
        return [
            'IdPlayer' => $id,
            'Status' => $status,
            'PlayerName' => [['Locale' => 'en-GB', 'Description' => $name]],
            'ShortName' => [['Locale' => 'en-GB', 'Description' => $name]],
        ];
    }
}
