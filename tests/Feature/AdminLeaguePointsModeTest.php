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

class AdminLeaguePointsModeTest extends TestCase
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
    public function create_always_stores_new_pointsmode_even_if_old_posted(): void
    {
        $service = $this->service();

        $result = $service->create([
            'league_title' => 'Neue Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'old',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
        ] + $this->numericOptionDefaults());

        $this->assertTrue($result['ok']);
        $league = League::query()->first();
        $this->assertNotNull($league);
        $this->assertSame('new', (string) LeagueOptions::query()
            ->where('options_league_id', $league->league_id)
            ->value('options_league_pointsmode'));
    }

    #[Test]
    public function update_preserves_existing_pointsmode_and_ignores_posted_value(): void
    {
        $league = League::query()->create([
            'league_title' => 'Alte Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => 'symbol_game_na.png',
        ]);

        LeagueOptions::query()->create([
            'options_league_id' => (int) $league->league_id,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_pointsmode' => 'old',
            'options_league_lcpoints' => '10,8,6,4,2,1',
        ] + $this->numericOptionDefaults());

        $result = $this->service()->update((int) $league->league_id, [
            'league_title' => 'Alte Liga Updated',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'points',
            'options_league_pricemode' => 'static',
            'options_league_pointsmode' => 'new',
            'options_league_lcpoints' => '12,10,8,7,6,5,4,3,2,1',
        ] + $this->numericOptionDefaults());

        $this->assertTrue($result['ok']);

        $options = LeagueOptions::query()->where('options_league_id', $league->league_id)->first();
        $this->assertNotNull($options);
        $this->assertSame('old', (string) $options->options_league_pointsmode);
        $this->assertSame('points', (string) $options->options_league_rankmode);
        $this->assertSame('static', (string) $options->options_league_pricemode);
        $this->assertSame('12,10,8,7,6,5,4,3,2,1', (string) $options->options_league_lcpoints);
    }

    #[Test]
    public function create_rejects_invalid_lc_points_list(): void
    {
        $result = $this->service()->create([
            'league_title' => 'Neue Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_lcpoints' => '12,ten,8',
        ] + $this->numericOptionDefaults());

        $this->assertFalse($result['ok']);
        $this->assertNotEmpty($result['errors'] ?? []);
    }

    #[Test]
    public function create_normalizes_spaced_lc_points_list(): void
    {
        $result = $this->service()->create([
            'league_title' => 'Neue Liga',
            'league_visible' => 1,
            'league_archive' => 0,
            'options_league_rankmode' => 'lc',
            'options_league_pricemode' => 'dynamic',
            'options_league_lcpoints' => '12, 10, 8, 7',
        ] + $this->numericOptionDefaults());

        $this->assertTrue($result['ok']);
        $this->assertSame(
            '12,10,8,7',
            (string) LeagueOptions::query()->value('options_league_lcpoints')
        );
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
            $table->string('league_symbol')->default('');
        });

        Schema::create('ffb_league_options', function (Blueprint $table) {
            $table->increments('options_id');
            $table->unsignedInteger('options_league_id');
            $table->string('options_league_rankmode')->default('lc');
            $table->string('options_league_pricemode')->default('dynamic');
            $table->string('options_league_pointsmode')->default('new');
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
        ];
    }
}
