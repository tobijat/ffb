<?php

namespace Tests\Feature;

use App\Services\AdminNewsService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminNewsTest extends TestCase
{
    public function test_news_admin_redirects_guests(): void
    {
        $this->get('/admin/news')
            ->assertRedirect(route('start', ['destination' => '/platform/admin/news']));
    }

    public function test_news_admin_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/news')
            ->assertRedirect(route('start'));
    }

    public function test_news_admin_renders_for_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminNewsService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544, null, 'create')->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_news.png',
                        'name' => 'News',
                        'link' => '/platform/admin/news',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'items' => [
                    [
                        'news_id' => 9,
                        'news_title' => 'Saisonstart',
                        'news_text_html' => 'Willkommen<br />',
                        'news_date' => '2026-09-01 12:00:00',
                        'news_symbol' => 'news.png',
                        'news_symbol_url' => '/images/ffb/symbols/news.png',
                        'news_priority' => 0,
                        'news_game_id' => 0,
                    ],
                ],
                'games' => [
                    ['game_id' => 0, 'game_title' => 'Global'],
                    ['game_id' => 26, 'game_title' => 'Testliga'],
                ],
                'form' => [
                    'news_id' => '',
                    'news_game_id' => 0,
                    'news_title' => '',
                    'news_text' => '',
                    'news_symbol' => '',
                    'news_priority' => '0',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/news')
            ->assertOk()
            ->assertSee('News', false)
            ->assertSee('Saisonstart', false)
            ->assertSee('Hinzufügen', false)
            ->assertSee('name="news_title"', false)
            ->assertSee('Soccer Sportsfan', false);
    }

    public function test_news_store_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminNewsService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => true,
                'message' => 'News erfolgreich hinzugefügt.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/news', [
                'news_game_id' => 0,
                'news_title' => 'Titel',
                'news_text' => 'Text',
                'news_symbol' => '',
                'news_priority' => '0',
            ])
            ->assertRedirect(route('admin.news'))
            ->assertSessionHas('admin_message', 'News erfolgreich hinzugefügt.');
    }

    public function test_news_store_shows_validation_errors(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminNewsService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn([
                'ok' => false,
                'errors' => ['Bitte alle mit * markierten Felder ausfüllen.'],
                'form' => [
                    'news_id' => '',
                    'news_game_id' => 0,
                    'news_title' => '',
                    'news_text' => '',
                    'news_symbol' => '',
                    'news_priority' => '0',
                ],
            ]);
            $mock->shouldReceive('pagePayload')->once()->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'items' => [],
                'games' => [['game_id' => 0, 'game_title' => 'Global']],
                'form' => [
                    'news_id' => '',
                    'news_game_id' => 0,
                    'news_title' => '',
                    'news_text' => '',
                    'news_symbol' => '',
                    'news_priority' => '0',
                ],
                'mode' => 'create',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->post('/admin/news', [
                'news_game_id' => 0,
                'news_title' => '',
                'news_text' => '',
            ])
            ->assertOk()
            ->assertSee('Bitte alle mit * markierten Felder ausfüllen.', false);
    }

    public function test_news_delete_redirects_on_success(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminNewsService::class, function ($mock) {
            $mock->shouldReceive('delete')->once()->with(9)->andReturn([
                'ok' => true,
                'message' => 'News erfolgreich gelöscht.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->delete('/admin/news/9')
            ->assertRedirect(route('admin.news'))
            ->assertSessionHas('admin_message', 'News erfolgreich gelöscht.');
    }
}
