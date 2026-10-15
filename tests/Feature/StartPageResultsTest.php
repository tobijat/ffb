<?php

namespace Tests\Feature;

use App\Services\StartPageService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StartPageResultsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        foreach (['ffb_match', 'ffb_team', 'ffb_matchround', 'ffb_league', 'ffb_userteam', 'web_user'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_date_llogin')->nullable();
        });

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->increments('userteam_id');
            $table->integer('userteam_score')->default(0);
        });

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->tinyInteger('league_test')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id')->default(0);
            $table->string('matchround_title')->default('');
            $table->tinyInteger('matchround_status')->default(1);
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_homescore_penalty')->default(-1);
            $table->integer('match_guestscore_penalty')->default(-1);
            $table->integer('match_minutes')->default(90);
            $table->string('match_date')->nullable();
        });
    }

    protected function tearDown(): void
    {
        foreach (['ffb_match', 'ffb_team', 'ffb_matchround', 'ffb_league', 'ffb_userteam', 'web_user'] as $table) {
            Schema::dropIfExists($table);
        }

        parent::tearDown();
    }

    #[Test]
    public function last_results_include_all_finished_matches_of_most_recent_round(): void
    {
        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
            ['team_id' => 3, 'team_name' => 'Gamma', 'team_nationality' => 'FRA'],
            ['team_id' => 4, 'team_name' => 'Delta', 'team_nationality' => 'ITA'],
            ['team_id' => 5, 'team_name' => 'Epsilon', 'team_nationality' => 'ESP'],
            ['team_id' => 6, 'team_name' => 'Zeta', 'team_nationality' => 'NED'],
        ]);

        // Older round with finished matches — must not appear once a newer round has results.
        DB::table('ffb_match')->insert([
            [
                'match_id' => 1,
                'match_round' => 10,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 1,
                'match_guestscore' => 0,
                'match_date' => '2026-01-01 18:00:00.000',
            ],
        ]);

        // Most recent round: 12 finished + 1 unfinished — show all 12 finished.
        $rows = [];
        for ($i = 0; $i < 12; $i++) {
            $rows[] = [
                'match_id' => 100 + $i,
                'match_round' => 20,
                'match_hometeam_id' => ($i % 2 === 0) ? 1 : 3,
                'match_guestteam_id' => ($i % 2 === 0) ? 2 : 4,
                'match_homescore' => 2,
                'match_guestscore' => 1,
                'match_date' => sprintf('2026-06-%02d 20:00:00.000', $i + 1),
            ];
        }
        $rows[] = [
            'match_id' => 200,
            'match_round' => 20,
            'match_hometeam_id' => 5,
            'match_guestteam_id' => 6,
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_date' => '2026-06-20 20:00:00.000',
        ];
        DB::table('ffb_match')->insert($rows);

        $payload = app(StartPageService::class)->payload();

        $this->assertCount(12, $payload['results']);
        $this->assertSame('12.06.2026', $payload['results'][0]['date']);
        $this->assertSame('01.06.2026', $payload['results'][11]['date']);
        $this->assertFalse(collect($payload['results'])->contains(
            fn (array $r): bool => $r['date'] === '01.01.2026'
        ));
        $this->assertFalse(collect($payload['results'])->contains(
            fn (array $r): bool => $r['home_team'] === 'Epsilon' || $r['guest_team'] === 'Zeta'
        ));
    }

    #[Test]
    public function last_results_format_penalty_and_overtime_like_match_list(): void
    {
        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
            ['team_id' => 3, 'team_name' => 'Gamma', 'team_nationality' => 'FRA'],
            ['team_id' => 4, 'team_name' => 'Delta', 'team_nationality' => 'ITA'],
        ]);

        DB::table('ffb_match')->insert([
            [
                'match_id' => 1,
                'match_round' => 5,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 1,
                'match_guestscore' => 1,
                'match_homescore_penalty' => 4,
                'match_guestscore_penalty' => 3,
                'match_minutes' => 120,
                'match_date' => '2026-07-02 20:00:00.000',
            ],
            [
                'match_id' => 2,
                'match_round' => 5,
                'match_hometeam_id' => 3,
                'match_guestteam_id' => 4,
                'match_homescore' => 2,
                'match_guestscore' => 1,
                'match_homescore_penalty' => -1,
                'match_guestscore_penalty' => -1,
                'match_minutes' => 120,
                'match_date' => '2026-07-01 20:00:00.000',
            ],
        ]);

        $payload = app(StartPageService::class)->payload();
        $this->assertCount(2, $payload['results']);

        $this->assertStringContainsString('4:3', $payload['results'][0]['score_html']);
        $this->assertStringContainsString('n.E.', $payload['results'][0]['score_html']);
        $this->assertStringContainsString('1:1', $payload['results'][0]['score_html']);
        $this->assertStringContainsString('n.V.', $payload['results'][0]['score_html']);
        $this->assertStringContainsString("url('/vendor/flag-icons/flags/4x3/at.svg')", $payload['results'][0]['home_flag_css']);

        $this->assertStringContainsString('2:1', $payload['results'][1]['score_html']);
        $this->assertStringContainsString('n.V.', $payload['results'][1]['score_html']);
        $this->assertStringNotContainsString('n.E.', $payload['results'][1]['score_html']);
    }
}
