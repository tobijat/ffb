<?php

namespace Tests\Unit;

use App\Support\RequestJsonArray;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RequestJsonArrayTest extends TestCase
{
    #[Test]
    public function prefers_json_payload_over_named_array(): void
    {
        $request = Request::create('/admin', 'POST', [
            'rows_json' => json_encode([
                ['team_id' => 1, 'team_uefa_id' => '47'],
                ['team_id' => 2, 'team_uefa_id' => '88'],
            ], JSON_THROW_ON_ERROR),
            'rows' => [
                ['team_id' => 99],
            ],
        ]);

        $rows = RequestJsonArray::pull($request, 'rows_json', 'rows');

        $this->assertCount(2, $rows);
        $this->assertSame(1, $rows[0]['team_id']);
        $this->assertSame('88', $rows[1]['team_uefa_id']);
    }

    #[Test]
    public function falls_back_to_named_array_when_json_missing(): void
    {
        $request = Request::create('/admin', 'POST', [
            'matches' => [
                ['match_round' => 12],
                'skip-me',
            ],
        ]);

        $rows = RequestJsonArray::pull($request, 'matches_json', 'matches');

        $this->assertCount(1, $rows);
        $this->assertSame(12, $rows[0]['match_round']);
    }

    #[Test]
    public function returns_empty_list_for_invalid_json(): void
    {
        $request = Request::create('/admin', 'POST', [
            'players_json' => '{not-json',
            'players' => [],
        ]);

        $this->assertSame([], RequestJsonArray::pull($request, 'players_json', 'players'));
    }
}
