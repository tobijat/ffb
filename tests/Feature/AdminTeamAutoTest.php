<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminTeamService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTeamAutoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_teamfid');
        Schema::dropIfExists('ffb_team');
        parent::tearDown();
    }

    #[Test]
    public function analyze_matchrounds_file_splits_present_and_missing_teams(): void
    {
        Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Deutschland',
            'team_nationality' => 'ger',
            'team_avg_price' => 5,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $file = UploadedFile::fake()->createWithContent(
            'nations.json',
            json_encode([
                'spieltage' => [
                    [
                        'spieltag' => 1,
                        'spiele' => [
                            ['datum' => '2026-09-24', 'heim' => 'Niederlande', 'gast' => 'Deutschland'],
                            ['datum' => '2026-09-24', 'heim' => 'Kosovo', 'gast' => 'Irland'],
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
        );

        $result = $this->service()->analyzeMatchroundsFile($file);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['auto']['present']);
        $this->assertSame('Deutschland', $result['auto']['present'][0]['team_name']);
        $this->assertCount(3, $result['auto']['missing']);

        $missingNames = array_column($result['auto']['missing'], 'team_name');
        $this->assertContains('Niederlande', $missingNames);
        $this->assertContains('Kosovo', $missingNames);
        $this->assertContains('Irland', $missingNames);

        $byName = [];
        foreach ($result['auto']['missing'] as $row) {
            $byName[$row['team_name']] = $row;
        }
        $this->assertSame('ned', $byName['Niederlande']['team_nationality']);
        $this->assertSame('kos', $byName['Kosovo']['team_nationality']);
        $this->assertSame('irl', $byName['Irland']['team_nationality']);
    }

    #[Test]
    public function create_missing_teams_inserts_rows(): void
    {
        $result = $this->service()->createMissingTeams([
            [
                'team_name' => 'Kosovo',
                'team_nationality' => 'kos',
                'team_price' => 6,
                'team_status' => 1,
            ],
            [
                'team_name' => 'Niederlande',
                'team_nationality' => 'ned',
                'team_price' => 5,
                'team_status' => 1,
            ],
        ]);

        $this->assertTrue($result['ok']);
        $this->assertSame('2 Teams erfolgreich hinzugefügt.', $result['message']);
        $this->assertDatabaseHas('ffb_team', [
            'team_name' => 'Kosovo',
            'team_nationality' => 'kos',
            'team_avg_price' => 6,
        ]);
        $this->assertDatabaseHas('ffb_team', [
            'team_name' => 'Niederlande',
            'team_nationality' => 'ned',
        ]);
    }

    private function service(): AdminTeamService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);

        return new AdminTeamService($adminCenter);
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_teamfid');
        Schema::dropIfExists('ffb_team');

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->double('team_avg_price')->default(5);
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_teamfid', function (Blueprint $table) {
            $table->increments('teamfid_id');
            $table->unsignedInteger('teamfid_team_id');
            $table->string('teamfid_fid_foe')->default('');
            $table->string('teamfid_fid_tm')->default('');
            $table->string('teamfid_fid_wf')->default('');
            $table->string('teamfid_name_foe')->default('');
            $table->string('teamfid_name_tm')->default('');
            $table->string('teamfid_name_wf')->default('');
            $table->string('teamfid_url_foe')->default('');
            $table->string('teamfid_url_tm')->default('');
            $table->string('teamfid_url_wf')->default('');
        });
    }
}
