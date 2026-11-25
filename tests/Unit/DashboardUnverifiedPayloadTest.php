<?php

namespace Tests\Unit;

use App\Services\DashboardService;
use App\Services\FfbPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardUnverifiedPayloadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        Schema::dropIfExists('ffb_poll');
        Schema::dropIfExists('ffb_news');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');

        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_email')->default('');
            $table->string('user_password')->default('');
            $table->string('user_status')->default('active');
            $table->timestamp('email_verified_at')->nullable();
        });

        Schema::create('ffb_user_details', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_details_ffb_selected_league')->default(0);
            $table->string('user_details_avatar')->nullable();
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('user_details_zip')->nullable();
            $table->string('user_details_city')->nullable();
            $table->string('user_details_street')->nullable();
            $table->string('user_details_phone')->nullable();
            $table->string('user_details_website')->nullable();
        });

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('Liga');
            $table->string('league_type')->default('nation');
            $table->integer('league_archive')->default(0);
            $table->integer('league_visible')->default(1);
            $table->integer('league_test')->default(0);
            $table->string('league_symbol')->nullable();
            $table->string('asset_key')->nullable();
        });

        Schema::create('ffb_news', function (Blueprint $table) {
            $table->increments('news_id');
            $table->integer('news_league_id')->default(0);
            $table->string('news_title')->default('');
            $table->text('news_text')->nullable();
            $table->string('news_date')->nullable();
            $table->integer('news_status')->default(1);
        });

        Schema::create('ffb_poll', function (Blueprint $table) {
            $table->increments('poll_id');
            $table->integer('poll_league_id')->default(0);
            $table->string('poll_type')->default('select');
            $table->integer('poll_status')->default(0);
        });

        Schema::create('web_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->unsignedInteger('admin_user_id');
            $table->string('admin_section')->default('ffb');
            $table->string('admin_status')->default('active');
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->integer('matchround_status')->default(1);
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_matchround');
        Schema::dropIfExists('web_admin');
        Schema::dropIfExists('ffb_poll');
        Schema::dropIfExists('ffb_news');
        Schema::dropIfExists('ffb_league');
        Schema::dropIfExists('ffb_user_details');
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_payload_for_unverified_user_hides_polls_and_forces_no_league(): void
    {
        $passwords = new FfbPassword;
        DB::table('web_user')->insert([
            'user_id' => 7,
            'user_nickname' => 'pending',
            'user_email' => 'pending@example.com',
            'user_password' => $passwords->hash('secret12ab'),
            'user_status' => 'pending',
            'email_verified_at' => null,
        ]);
        DB::table('ffb_user_details')->insert([
            'user_id' => 7,
            'user_details_ffb_selected_league' => 9,
        ]);
        DB::table('ffb_league')->insert([
            'league_id' => 9,
            'league_title' => 'Testliga',
            'league_visible' => 1,
            'league_test' => 0,
            'league_archive' => 0,
        ]);
        DB::table('ffb_matchround')->insert([
            'matchround_id' => 1,
            'matchround_league_id' => 9,
            'matchround_status' => 1,
        ]);

        $payload = $this->app->make(DashboardService::class)->payload(7);

        $this->assertTrue($payload['email_unverified']);
        $this->assertSame(0, $payload['selected_league_id']);
        $this->assertNull($payload['polls']['text']);
        $this->assertNull($payload['polls']['select']);
        $this->assertNotEmpty($payload['leagues']);
        $this->assertTrue((bool) $payload['leagues'][0]['is_faded']);

        $select = $this->app->make(DashboardService::class)->selectLeague(7, 9);
        $this->assertFalse($select['ok']);
        $this->assertSame(403, $select['status']);
    }
}
