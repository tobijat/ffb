<?php

namespace Tests\Feature;

use App\Models\MatchGame;
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
    public function format_display_date_includes_known_kickoff_time(): void
    {
        $this->assertSame(
            '24.09.2026 18:00',
            MatchGame::formatDisplayDate('2026-09-24 18:00:00.000'),
        );
        $this->assertFalse(MatchGame::hasKnownKickoffTime('2026-09-24 '.MatchGame::DEFAULT_TIME));
        $this->assertTrue(MatchGame::hasKnownKickoffTime('2026-09-24 18:00:00.000'));
    }
}
