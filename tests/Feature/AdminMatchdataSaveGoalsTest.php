<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\League;
use App\Models\LeagueOptions;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Player;
use App\Models\Playerstats;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminMatchdataService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMatchdataSaveGoalsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function save_player_stats_uses_match_league_pointsmode_not_global_fallback(): void
    {
        // Global fallback still uses the legacy "old" pointsmode (count only).
        LeagueOptions::query()->create([
            'options_league_id' => 0,
            'options_league_pointsmode' => 'old',
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_threshold_upper' => 60,
            'options_score_minutes_low' => 1,
            'options_score_minutes_middle' => 2,
            'options_score_minutes_high' => 3,
            'options_score_owngoals' => -2,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
        ]);

        $league = League::query()->create([
            'league_title' => 'EM Test',
            'league_visible' => 1,
            'league_archive' => 0,
        ]);
        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_pointsmode' => 'new',
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_threshold_upper' => 60,
            'options_score_minutes_low' => 1,
            'options_score_minutes_middle' => 2,
            'options_score_minutes_high' => 3,
            'options_score_owngoals' => -2,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
        ]);

        $home = Team::query()->create(['team_name' => 'Home', 'team_status' => 1]);
        $guest = Team::query()->create(['team_name' => 'Guest', 'team_status' => 1]);
        $round = Matchround::query()->create([
            'matchround_league_id' => (int) $league->league_id,
            'matchround_title' => 'Finale',
            'matchround_startdate' => '2026-07-01 00:00:00',
            'matchround_enddate' => '2026-07-02 00:00:00',
            'matchround_status' => 1,
        ]);
        $match = MatchGame::query()->create([
            'match_round' => (int) $round->matchround_id,
            'match_hometeam_id' => (int) $home->team_id,
            'match_guestteam_id' => (int) $guest->team_id,
            'match_date' => '2026-07-01 20:00:00',
            'match_homescore' => 2,
            'match_guestscore' => 0,
            'match_minutes' => 90,
        ]);

        $player = Player::query()->create([
            'player_fname' => 'Tor',
            'player_lname' => 'Schütze',
        ]);
        $playerteam = Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $home->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_status' => 1,
            'playerteam_player_position' => 's',
        ]);

        // No admin session / selected league: previously fell back to options_league_id=0 ("old").
        $result = app(AdminMatchdataService::class)->savePlayerStats(
            (int) $match->match_id,
            (int) $playerteam->playerteam_id,
            [
                'minutes' => 90,
                'minute_in' => 1,
                'minute_out' => 90,
                'goals' => '12;87',
                'owngoals' => '0',
                'assists' => 0,
                'cards' => 'n',
                'penaltieslost' => 0,
                'penaltiessaved' => 0,
                'penaltyshootout_save' => 0,
                'penaltyshootout_lost' => 0,
                'penaltyshootout_hit' => 0,
            ],
        );

        $this->assertTrue($result['ok'] ?? false, implode('; ', $result['errors'] ?? []));

        $stat = Playerstats::query()
            ->where('playerstats_match_id', (int) $match->match_id)
            ->where('playerstats_playerteam_id', (int) $playerteam->playerteam_id)
            ->first();

        $this->assertNotNull($stat);
        $this->assertSame(2, (int) $stat->playerstats_goals);

        $minutes = Goal::query()
            ->where('goal_match_id', (int) $match->match_id)
            ->where('goal_playerteam_id', (int) $playerteam->playerteam_id)
            ->where('goal_owngoal', 0)
            ->orderBy('goal_minute')
            ->pluck('goal_minute')
            ->map(static fn ($m): int => (int) $m)
            ->all();

        $this->assertSame([12, 87], $minutes);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_psgoal');
        Schema::dropIfExists('ffb_goal');
        Schema::dropIfExists('ffb_playerstats');
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_match');
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->unsignedInteger('options_league_id');
            $table->string('options_league_pointsmode')->default('new');
            $table->integer('options_score_minutes_threshold_lower')->default(30);
            $table->integer('options_score_minutes_threshold_upper')->default(60);
            $table->integer('options_score_minutes_low')->default(1);
            $table->integer('options_score_minutes_middle')->default(2);
            $table->integer('options_score_minutes_high')->default(3);
            $table->integer('options_score_goals_g')->default(6);
            $table->integer('options_score_goals_d')->default(5);
            $table->integer('options_score_goals_m')->default(4);
            $table->integer('options_score_goals_s')->default(4);
            $table->integer('options_score_assists')->default(3);
            $table->integer('options_score_owngoals')->default(-2);
            $table->integer('options_score_no_oppgoals_g')->default(4);
            $table->integer('options_score_no_oppgoals_d')->default(3);
            $table->integer('options_score_no_oppgoals_m')->default(1);
            $table->integer('options_score_oppgoals_g')->default(-1);
            $table->integer('options_score_oppgoals_d')->default(-1);
            $table->integer('options_score_card_y')->default(-2);
            $table->integer('options_score_card_yr')->default(-4);
            $table->integer('options_score_card_r')->default(-5);
            $table->integer('options_score_penalty_saved')->default(2);
            $table->integer('options_score_penalty_lost')->default(-2);
            $table->integer('options_score_penaltyshootout_save')->default(2);
            $table->integer('options_score_penaltyshootout_lost')->default(-2);
            $table->integer('options_score_penaltyshootout_hit')->default(2);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->timestamp('matchround_startdate')->nullable();
            $table->timestamp('matchround_enddate')->nullable();
            $table->tinyInteger('matchround_status')->default(1);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round')->default(0);
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
            $table->string('match_date')->nullable();
            $table->integer('match_homescore')->default(-1);
            $table->integer('match_guestscore')->default(-1);
            $table->integer('match_homescore_penalty')->default(-1);
            $table->integer('match_guestscore_penalty')->default(-1);
            $table->integer('match_minutes')->default(0);
            $table->string('match_status')->default('');
            $table->string('match_url')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_name')->default('');
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id')->default(0);
            $table->unsignedInteger('playerteam_team_id')->default(0);
            $table->unsignedInteger('playerteam_league_id')->default(0);
            $table->tinyInteger('playerteam_status')->default(1);
            $table->string('playerteam_player_position', 8)->default('');
        });

        Schema::create('ffb_playerstats', function (Blueprint $table) {
            $table->increments('playerstats_id');
            $table->unsignedInteger('playerstats_playerteam_id');
            $table->unsignedInteger('playerstats_matchround_id');
            $table->unsignedInteger('playerstats_match_id')->nullable();
            $table->integer('playerstats_minutes')->default(0);
            $table->integer('playerstats_minute_in')->default(0);
            $table->integer('playerstats_minute_out')->default(0);
            $table->integer('playerstats_goals')->default(0);
            $table->integer('playerstats_assists')->default(0);
            $table->integer('playerstats_owngoals')->default(0);
            $table->string('playerstats_cards', 8)->default('n');
            $table->integer('playerstats_penaltieslost')->default(0);
            $table->integer('playerstats_penaltiessaved')->default(0);
            $table->integer('playerstats_penaltyshootout_save')->default(0);
            $table->integer('playerstats_penaltyshootout_lost')->default(0);
            $table->integer('playerstats_penaltyshootout_hit')->default(0);
            $table->integer('playerstats_score_goals')->default(0);
            $table->integer('playerstats_score_assists')->default(0);
            $table->integer('playerstats_score_minutes')->default(0);
            $table->integer('playerstats_score_cards')->default(0);
            $table->integer('playerstats_score_owngoals')->default(0);
            $table->integer('playerstats_score_penaltieslost')->default(0);
            $table->integer('playerstats_score_penaltiessaved')->default(0);
            $table->integer('playerstats_score_oppgoals')->default(0);
            $table->integer('playerstats_score_nooppgoals')->default(0);
            $table->integer('playerstats_score_penaltyshootout_save')->default(0);
            $table->integer('playerstats_score_penaltyshootout_lost')->default(0);
            $table->integer('playerstats_score_penaltyshootout_hit')->default(0);
            $table->integer('playerstats_score')->default(0);
        });

        Schema::create('ffb_goal', function (Blueprint $table) {
            $table->increments('goal_id');
            $table->unsignedInteger('goal_match_id');
            $table->unsignedInteger('goal_playerteam_id')->default(0);
            $table->integer('goal_minute')->default(0);
            $table->tinyInteger('goal_owngoal')->default(0);
            $table->tinyInteger('goal_penalty')->default(0);
            $table->tinyInteger('goal_penaltyshootout')->default(0);
        });

        Schema::create('ffb_psgoal', function (Blueprint $table) {
            $table->increments('psgoal_id');
            $table->unsignedInteger('psgoal_match_id');
            $table->unsignedInteger('psgoal_playerteam_id')->default(0);
            $table->integer('psgoal_minute')->default(120);
            $table->tinyInteger('psgoal_hit')->default(0);
            $table->tinyInteger('psgoal_fail')->default(0);
        });
    }
}
