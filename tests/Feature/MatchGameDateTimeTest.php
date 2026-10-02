<?php

namespace Tests\Feature;

use App\Models\MatchGame;
use App\Models\Team;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MatchGameDateTimeTest extends TestCase
{
    #[Test]
    public function compose_date_time_uses_sentinel_when_time_missing(): void
    {
        $this->assertSame(
            '2026-09-24 11:11:11.111',
            MatchGame::composeDateTime('2026-09-24'),
        );
    }

    #[Test]
    public function compose_date_time_preserves_and_normalizes_fractional_seconds(): void
    {
        $this->assertSame(
            '2026-09-24 18:00:00.000',
            MatchGame::composeDateTime('2026-09-24 18:00:00'),
        );
        $this->assertSame(
            '2026-09-24 18:00:00.500',
            MatchGame::composeDateTime('2026-09-24T18:00:00.5'),
        );
    }

    #[Test]
    public function calendar_date_strips_time(): void
    {
        $this->assertSame('2026-09-24', MatchGame::calendarDate('2026-09-24 18:00:00.000'));
        $this->assertSame('2026-09-24', MatchGame::calendarDate('2026-09-24'));
        $this->assertSame('', MatchGame::calendarDate(''));
    }

    #[Test]
    public function format_display_date_omits_sentinel_time(): void
    {
        $this->assertSame(
            '24.09.2026',
            MatchGame::formatDisplayDate('2026-09-24 '.MatchGame::DEFAULT_TIME),
        );
        $this->assertSame('24.09.2026', MatchGame::formatDisplayDate('2026-09-24'));
    }

    #[Test]
    public function format_display_date_only_never_includes_time(): void
    {
        $this->assertSame(
            '24.09.2026',
            MatchGame::formatDisplayDateOnly('2026-09-24 18:00:00.000'),
        );
        $this->assertSame(
            '24.09.2026',
            MatchGame::formatDisplayDateOnly('2026-09-24 '.MatchGame::DEFAULT_TIME),
        );
    }

    #[Test]
    public function format_display_date_includes_known_kickoff_time(): void
    {
        $this->assertSame(
            '24.09.2026 18:00',
            MatchGame::formatDisplayDate('2026-09-24 18:00:00.000'),
        );
        $this->assertFalse(MatchGame::hasKnownKickoffTime('2026-09-24 '.MatchGame::DEFAULT_TIME));
        $this->assertTrue(MatchGame::hasKnownKickoffTime('2026-09-24 18:00:00.000'));
    }

    #[Test]
    public function side_list_payload_uses_display_date_and_minutes(): void
    {
        $home = new Team([
            'team_id' => 1,
            'team_name' => 'Alpha',
            'team_nationality' => 'aut',
        ]);
        $guest = new Team([
            'team_id' => 2,
            'team_name' => 'Beta',
            'team_nationality' => 'ger',
        ]);

        $match = new MatchGame([
            'match_id' => 9,
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_homescore' => 1,
            'match_guestscore' => 1,
            'match_homescore_penalty' => 4,
            'match_guestscore_penalty' => 3,
            'match_date' => '2026-09-24 18:00:00.000',
            'match_minutes' => 120,
            'match_status' => 1,
        ]);
        $match->setRelation('homeTeam', $home);
        $match->setRelation('guestTeam', $guest);
        $match->match_id = 9;

        $payload = $match->toSideListPayload();

        $this->assertSame(9, $payload['match_id']);
        $this->assertSame('24.09.2026', $payload['match_date']);
        $this->assertSame('18:00', $payload['match_time']);
        $this->assertSame('Alpha', $payload['match_hometeam_name']);
        $this->assertSame('Beta', $payload['match_guestteam_name']);
        $this->assertSame(120, $payload['match_minutes']);
        $this->assertSame(1, $payload['match_homescore']);
        $this->assertSame(4, $payload['match_homescore_penalty']);
    }

    #[Test]
    public function side_list_payload_omits_sentinel_kickoff_time(): void
    {
        $match = new MatchGame([
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_date' => '2026-09-24 '.MatchGame::DEFAULT_TIME,
            'match_minutes' => 90,
        ]);
        $match->match_id = 3;
        $match->setRelation('homeTeam', null);
        $match->setRelation('guestTeam', null);

        $payload = $match->toSideListPayload();

        $this->assertSame('24.09.2026', $payload['match_date']);
        $this->assertSame('', $payload['match_time']);
        $this->assertSame('18:00', MatchGame::formatDisplayTime('2026-09-24 18:00:00.000'));
        $this->assertNull(MatchGame::formatDisplayTime('2026-09-24 '.MatchGame::DEFAULT_TIME));
    }
}
