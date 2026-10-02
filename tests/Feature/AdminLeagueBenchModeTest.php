<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\LeagueOptions;
use App\Services\AdminCenterService;
use App\Services\AdminLeagueService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminLeagueBenchModeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');
        parent::tearDown();
    }

    #[Test]
    public function create_stores_null_benchmode_by_default_and_zeros_bench_limits(): void
    {
        $result = $this->service()->create([
            'league_title' => 'Bench Off',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
            'options_lineup_min_bench' => 3,
            'options_lineup_max_bench' => 5,
        ] + $this->numericOptionDefaults());

        $this->assertTrue($result['ok']);

        $options = LeagueOptions::query()->first();
        $this->assertNotNull($options);
        $this->assertNull($options->options_league_benchmode);
        $this->assertSame(0, (int) $options->options_lineup_min_bench);
        $this->assertSame(0, (int) $options->options_lineup_max_bench);
    }

    #[Test]
    public function create_and_update_persist_cover_and_bestof_benchmode(): void
    {
        $create = $this->service()->create([
            'league_title' => 'Bench Cover',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_benchmode' => 'cover',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
            'options_lineup_min_bench' => 1,
            'options_lineup_max_bench' => 3,
        ] + $this->numericOptionDefaults());

        $this->assertTrue($create['ok']);
        $league = League::query()->first();
        $this->assertNotNull($league);

        $options = LeagueOptions::query()->where('options_league_id', $league->league_id)->first();
        $this->assertNotNull($options);
        $this->assertSame('cover', (string) $options->options_league_benchmode);
        $this->assertSame(1, (int) $options->options_lineup_min_bench);
        $this->assertSame(3, (int) $options->options_lineup_max_bench);

        $form = $this->service()->formForEdit((int) $league->league_id);
        $this->assertNotNull($form);
        $this->assertSame('cover', $form['options_league_benchmode']);

        $update = $this->service()->update((int) $league->league_id, [
            'league_title' => 'Bench Best Of',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_benchmode' => 'bestof',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
            'options_lineup_min_bench' => 2,
            'options_lineup_max_bench' => 4,
        ] + $this->numericOptionDefaults());

        $this->assertTrue($update['ok']);
        $options->refresh();
        $this->assertSame('bestof', (string) $options->options_league_benchmode);
        $this->assertSame(2, (int) $options->options_lineup_min_bench);
        $this->assertSame(4, (int) $options->options_lineup_max_bench);
    }

    #[Test]
    public function update_to_aus_clears_benchmode_and_bench_limits(): void
    {
        $league = League::query()->create([
            'league_title' => 'With Bench',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => 'symbol_game_na.png',
        ]);

        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'new',
            'options_league_benchmode' => 'cover',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
            'options_lineup_min_bench' => 2,
            'options_lineup_max_bench' => 3,
        ] + $this->numericOptionDefaults());

        $result = $this->service()->update((int) $league->league_id, [
            'league_title' => 'With Bench',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_benchmode' => '',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
        ] + $this->numericOptionDefaults());

        $this->assertTrue($result['ok']);

        $options = LeagueOptions::query()->where('options_league_id', $league->league_id)->first();
        $this->assertNotNull($options);
        $this->assertNull($options->options_league_benchmode);
        $this->assertSame(0, (int) $options->options_lineup_min_bench);
        $this->assertSame(0, (int) $options->options_lineup_max_bench);
    }

    #[Test]
    public function invalid_benchmode_is_rejected(): void
    {
        $result = $this->service()->create([
            'league_title' => 'Bad Bench',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_benchmode' => 'nope',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
        ] + $this->numericOptionDefaults());

        $this->assertFalse($result['ok']);
        $this->assertContains('Ungültiger Ersatzbank-Modus.', $result['errors'] ?? []);
    }

    private function service(): AdminLeagueService
    {
        $center = Mockery::mock(AdminCenterService::class);

        return new AdminLeagueService($center);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_league_options');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->tinyInteger('league_test')->default(0);
            $table->string('league_symbol')->default('');
            $table->string('league_uefa_competition_identifier')->default('');
            $table->string('league_fifa_competition_identifier')->default('');
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->unsignedInteger('options_league_id');
            $table->string('options_league_rankmode')->default('lc');
            $table->string('options_league_pricemode')->default('dynamic');
            $table->string('options_league_pointsmode')->default('new');
            $table->string('options_league_benchmode')->nullable();
            $table->string('options_league_lcpoints')->default('12,10,8,7,6,5,4,3,2,1');

            foreach (array_merge(
                ['options_league_remind_hours_before' => 0],
                $this->numericOptionDefaults()
            ) as $column => $default) {
                $table->integer($column)->default((int) $default);
            }
        });
    }

    /**
     * @return array<string, int>
     */
    private function numericOptionDefaults(): array
    {
        return [
            'options_league_remind_hours_before' => 0,
            'options_score_minutes_threshold_upper' => 60,
            'options_score_minutes_threshold_lower' => 30,
            'options_score_minutes_high' => 3,
            'options_score_minutes_middle' => 2,
            'options_score_minutes_low' => 1,
            'options_score_goals_g' => 6,
            'options_score_goals_d' => 5,
            'options_score_goals_m' => 4,
            'options_score_goals_s' => 4,
            'options_score_assists' => 3,
            'options_score_owngoals' => -2,
            'options_score_no_oppgoals_g' => 4,
            'options_score_no_oppgoals_d' => 3,
            'options_score_no_oppgoals_m' => 1,
            'options_score_oppgoals_g' => -1,
            'options_score_oppgoals_d' => -1,
            'options_score_card_y' => -2,
            'options_score_card_yr' => -4,
            'options_score_card_r' => -5,
            'options_score_penalty_saved' => 2,
            'options_score_penalty_lost' => -2,
            'options_score_penaltyshootout_save' => 2,
            'options_score_penaltyshootout_lost' => -2,
            'options_score_penaltyshootout_hit' => 2,
            'options_lineup_max_players' => 11,
            'options_lineup_max_credits' => 100,
            'options_lineup_max_players_team' => 2,
            'options_lineup_max_g' => 1,
            'options_lineup_max_d' => 5,
            'options_lineup_max_m' => 5,
            'options_lineup_max_s' => 3,
            'options_lineup_min_g' => 1,
            'options_lineup_min_d' => 3,
            'options_lineup_min_m' => 3,
            'options_lineup_min_s' => 1,
            'options_lineup_min_bench' => 0,
            'options_lineup_max_bench' => 0,
        ];
    }
}
