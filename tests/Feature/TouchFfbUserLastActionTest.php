<?php

namespace Tests\Feature;

use App\Http\Middleware\TouchFfbUserLastAction;
use App\Services\FfbAuth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TouchFfbUserLastActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        Schema::dropIfExists('web_user');
        Schema::create('web_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nickname')->default('');
            $table->string('user_date_laction')->nullable();
            $table->string('user_date_llogin')->nullable();
        });

        DB::table('web_user')->insert([
            'user_id' => 544,
            'user_nickname' => 'tester',
            'user_date_laction' => '2020-01-01 00:00:00',
            'user_date_llogin' => '2020-01-01 00:00:00',
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('web_user');
        parent::tearDown();
    }

    public function test_logged_in_request_updates_user_date_laction(): void
    {
        $before = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_laction');
        $lloginBefore = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_llogin');

        $request = Request::create('/help', 'GET');
        $request->setLaravelSession($this->app['session']->driver());
        $request->session()->put(FfbAuth::SESSION_USER_ID, 544);

        $middleware = $this->app->make(TouchFfbUserLastAction::class);
        $middleware->handle($request, fn () => new Response('ok'));

        $after = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_laction');
        $lloginAfter = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_llogin');

        $this->assertNotSame($before, $after);
        $this->assertSame($lloginBefore, $lloginAfter);
        $this->assertEqualsWithDelta(time(), strtotime($after), 5);
    }

    public function test_guest_request_does_not_change_user_date_laction(): void
    {
        $before = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_laction');

        $request = Request::create('/help', 'GET');
        $request->setLaravelSession($this->app['session']->driver());

        $middleware = $this->app->make(TouchFfbUserLastAction::class);
        $middleware->handle($request, fn () => new Response('ok'));

        $after = (string) DB::table('web_user')->where('user_id', 544)->value('user_date_laction');
        $this->assertSame($before, $after);
    }
}
