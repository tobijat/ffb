<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Services\EloRatingClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloRatingClientTest extends TestCase
{
    private string $mapPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapPath = storage_path('framework/testing/elo-teams.csv');
        if (! is_dir(dirname($this->mapPath))) {
            mkdir(dirname($this->mapPath), 0777, true);
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_team');
        if (is_file($this->mapPath)) {
            unlink($this->mapPath);
        }
        parent::tearDown();
    }

    #[Test]
    public function loads_ratings_from_tsv_feeds_and_maps_team_ids(): void
    {
        file_put_contents($this->mapPath, '10;Argentinien;ARG;Argentina;;20;Oesterreich;AUT;Austria;;');

        Http::fake([
            'www.eloratings.net/World.tsv' => Http::response(
                "1\t1\tAR\t2173\t1\n2\t2\tAT\t1701\t2\n3\t3\tXX\t1000\t3\n",
                200,
            ),
            'www.eloratings.net/en.teams.tsv' => Http::response(
                "AR\tArgentina\nAT\tAustria\nXX\tUnknownland\n",
                200,
            ),
        ]);

        $client = new EloRatingClient(
            'https://www.eloratings.net/World.tsv',
            $this->mapPath,
            'https://www.eloratings.net/en.teams.tsv',
        );

        $this->assertSame(2173.0, $client->getEloRatingForTeam(10));
        $this->assertSame(1701.0, $client->getEloRatingForTeam(20));
        $this->assertNull($client->getEloRatingForTeam(99));

        $list = $client->ratingsForTeamList([20, 10, 99]);
        $this->assertSame([
            ['team_id' => 20, 'elo_rating' => 1701.0],
            ['team_id' => 10, 'elo_rating' => 2173.0],
        ], $list);
    }

    #[Test]
    public function resolves_legacy_world_html_url_to_tsv(): void
    {
        file_put_contents($this->mapPath, '10;Argentinien;ARG;Argentina;;');

        Http::fake([
            'www.eloratings.net/World.tsv' => Http::response("1\t1\tAR\t2000\t1\n", 200),
            'www.eloratings.net/en.teams.tsv' => Http::response("AR\tArgentina\n", 200),
        ]);

        $client = new EloRatingClient(
            'http://www.eloratings.net/world.html',
            $this->mapPath,
            'https://www.eloratings.net/en.teams.tsv',
        );

        $this->assertSame(2000.0, $client->getEloRatingForTeam(10));
        Http::assertSent(fn ($request): bool => str_contains($request->url(), 'World.tsv'));
        Http::assertNotSent(fn ($request): bool => str_contains($request->url(), 'world.html'));
    }

    #[Test]
    public function maps_teams_missing_from_csv_via_local_team_name(): void
    {
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->integer('team_id')->primary();
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Team::query()->insert([
            'team_id' => 163,
            'team_foreign_id' => '',
            'team_name' => 'Kosovo',
            'team_nationality' => 'kos',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        file_put_contents($this->mapPath, '10;Argentinien;ARG;Argentina;;');

        Http::fake([
            'www.eloratings.net/World.tsv' => Http::response(
                "1\t1\tAR\t2173\t1\n42\t42\tKO\t1714\t37\n",
                200,
            ),
            'www.eloratings.net/en.teams.tsv' => Http::response(
                "AR\tArgentina\nKO\tKosovo\n",
                200,
            ),
        ]);

        $client = new EloRatingClient(
            'https://www.eloratings.net/World.tsv',
            $this->mapPath,
            'https://www.eloratings.net/en.teams.tsv',
        );

        $this->assertSame(2173.0, $client->getEloRatingForTeam(10));
        $this->assertSame(1714.0, $client->getEloRatingForTeam(163));
    }

    #[Test]
    public function loads_team_map_from_local_csv_including_north_macedonia(): void
    {
        file_put_contents(
            $this->mapPath,
            "10;Argentinien;ARG;Argentina;;\n70;Nordmazedonien;MKD;North Macedonia;;\n70;Nordmazedonien;MKD;Macedonia;;\n",
        );

        Http::fake([
            'www.eloratings.net/World.tsv' => Http::response(
                "1\t1\tAR\t2173\t1\n2\t2\tNM\t1589\t2\n",
                200,
            ),
            'www.eloratings.net/2014.tsv' => Http::response(
                "1\t1\tAR\t2000\t1\n89\t89\tMK\t1481\t2\n",
                200,
            ),
            'www.eloratings.net/en.teams.tsv' => Http::response(
                "AR\tArgentina\nNM\tNorth Macedonia\tN Macedonia\nMK\tMacedonia\n",
                200,
            ),
        ]);

        $modern = new EloRatingClient(
            'https://www.eloratings.net/World.tsv',
            $this->mapPath,
            'https://www.eloratings.net/en.teams.tsv',
        );
        $this->assertSame(2173.0, $modern->getEloRatingForTeam(10));
        $this->assertSame(1589.0, $modern->getEloRatingForTeam(70));

        $historical = new EloRatingClient(
            'http://www.eloratings.net/2014.tsv',
            $this->mapPath,
            'https://www.eloratings.net/en.teams.tsv',
        );
        $this->assertSame(1481.0, $historical->getEloRatingForTeam(70));
        Http::assertNotSent(fn ($request): bool => str_contains($request->url(), 'teams.csv'));
    }
}
