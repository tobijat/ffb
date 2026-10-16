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
            $table->string('asset_key')->default('');
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
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'asset_key' => 'wm-2026-qkb1',
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_test' => 0,
        ]);
        DB::table('ffb_matchround')->insert([
            [
                'matchround_id' => 10,
                'matchround_league_id' => 1,
                'matchround_title' => 'Alt',
                'matchround_status' => 1,
            ],
            [
                'matchround_id' => 20,
                'matchround_league_id' => 1,
                'matchround_title' => 'Achtelfinale',
                'matchround_status' => 1,
            ],
        ]);

        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
            ['team_id' => 3, 'team_name' => 'Gamma', 'team_nationality' => 'FRA'],
            ['team_id' => 4, 'team_name' => 'Delta', 'team_nationality' => 'ITA'],
            ['team_id' => 5, 'team_name' => 'Epsilon', 'team_nationality' => 'ESP'],
            ['team_id' => 6, 'team_name' => 'Zeta', 'team_nationality' => 'NED'],
        ]);

        // Previous round — included after current when current is only partly finished.
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

        // Most recent round: 12 finished + 1 unfinished — show 12 current then previous.
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

        $this->assertCount(13, $payload['results']);
        $this->assertSame('12.06.2026', $payload['results'][0]['date']);
        $this->assertSame('01.06.2026', $payload['results'][11]['date']);
        $this->assertSame('01.01.2026', $payload['results'][12]['date']);
        $this->assertSame('WM 2026 - Achtelfinale', $payload['results'][0]['round_label']);
        $this->assertSame('WM 2026 - Alt', $payload['results'][12]['round_label']);
        $this->assertFalse(collect($payload['results'])->contains(
            fn (array $r): bool => $r['home_team'] === 'Epsilon' || $r['guest_team'] === 'Zeta'
        ));
    }

    #[Test]
    public function combined_partial_rounds_are_capped_at_26(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'asset_key' => 'wm-2026-qkb1',
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_test' => 0,
        ]);
        DB::table('ffb_matchround')->insert([
            ['matchround_id' => 10, 'matchround_league_id' => 1, 'matchround_title' => 'Alt', 'matchround_status' => 1],
            ['matchround_id' => 20, 'matchround_league_id' => 1, 'matchround_title' => 'Neu', 'matchround_status' => 1],
        ]);
        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
        ]);

        $rows = [];
        for ($i = 0; $i < 20; $i++) {
            $rows[] = [
                'match_id' => 1000 + $i,
                'match_round' => 10,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 1,
                'match_guestscore' => 0,
                'match_date' => sprintf('2026-05-%02d 18:00:00.000', $i + 1),
            ];
        }
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'match_id' => 2000 + $i,
                'match_round' => 20,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 2,
                'match_guestscore' => 1,
                'match_date' => sprintf('2026-06-%02d 18:00:00.000', $i + 1),
            ];
        }
        $rows[] = [
            'match_id' => 2999,
            'match_round' => 20,
            'match_hometeam_id' => 1,
            'match_guestteam_id' => 2,
            'match_homescore' => -1,
            'match_guestscore' => -1,
            'match_date' => '2026-06-28 18:00:00.000',
        ];
        DB::table('ffb_match')->insert($rows);

        $payload = app(StartPageService::class)->payload();

        $this->assertCount(26, $payload['results']);
        $this->assertSame('WM 2026 - Neu', $payload['results'][0]['round_label']);
        $this->assertSame('10.06.2026', $payload['results'][0]['date']);
        $this->assertSame('WM 2026 - Alt', $payload['results'][25]['round_label']);
    }

    #[Test]
    public function complete_single_round_is_not_capped_at_26(): void
    {
        DB::table('ffb_league')->insert([
            'league_id' => 1,
            'asset_key' => 'wm-2026-qkb1',
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_test' => 0,
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 30,
            'matchround_league_id' => 1,
            'matchround_title' => 'Gruppenphase',
            'matchround_status' => 1,
        ]);
        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
        ]);

        $rows = [];
        for ($i = 0; $i < 30; $i++) {
            $rows[] = [
                'match_id' => 3000 + $i,
                'match_round' => 30,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 1,
                'match_guestscore' => 0,
                'match_date' => sprintf('2026-07-%02d 18:00:00.000', ($i % 28) + 1),
            ];
        }
        DB::table('ffb_match')->insert($rows);

        $payload = app(StartPageService::class)->payload();

        $this->assertCount(30, $payload['results']);
        $this->assertSame('WM 2026 - Gruppenphase', $payload['results'][0]['round_label']);
    }

    #[Test]
    public function leagues_are_ordered_by_most_recent_or_upcoming_match(): void
    {
        DB::table('ffb_league')->insert([
            [
                'league_id' => 1,
                'asset_key' => 'old-liga',
                'league_title' => 'Alte Liga',
                'league_visible' => 1,
                'league_archive' => 0,
                'league_test' => 0,
            ],
            [
                'league_id' => 2,
                'asset_key' => 'future-liga',
                'league_title' => 'Kommende Liga',
                'league_visible' => 1,
                'league_archive' => 0,
                'league_test' => 0,
            ],
            [
                'league_id' => 3,
                'asset_key' => 'empty-liga',
                'league_title' => 'Leere Liga',
                'league_visible' => 1,
                'league_archive' => 0,
                'league_test' => 0,
            ],
        ]);
        DB::table('ffb_matchround')->insert([
            ['matchround_id' => 1, 'matchround_league_id' => 1, 'matchround_title' => 'R1', 'matchround_status' => 1],
            ['matchround_id' => 2, 'matchround_league_id' => 2, 'matchround_title' => 'R1', 'matchround_status' => 1],
        ]);
        DB::table('ffb_team')->insert([
            ['team_id' => 1, 'team_name' => 'Alpha', 'team_nationality' => 'AUT'],
            ['team_id' => 2, 'team_name' => 'Beta', 'team_nationality' => 'GER'],
        ]);
        DB::table('ffb_match')->insert([
            [
                'match_id' => 1,
                'match_round' => 1,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => 1,
                'match_guestscore' => 0,
                'match_date' => '2026-01-01 18:00:00.000',
            ],
            [
                'match_id' => 2,
                'match_round' => 2,
                'match_hometeam_id' => 1,
                'match_guestteam_id' => 2,
                'match_homescore' => -1,
                'match_guestscore' => -1,
                'match_date' => '2026-12-01 18:00:00.000',
            ],
        ]);

        $payload = app(StartPageService::class)->payload();
        $titles = array_column($payload['leagues'], 'league_title');

        $this->assertSame(['Kommende Liga', 'Alte Liga', 'Leere Liga'], $titles);
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
