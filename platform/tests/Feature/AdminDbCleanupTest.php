<?php

namespace Tests\Feature;

use App\Services\AdminDbCleanupService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminDbCleanupTest extends TestCase
{
    public function test_db_cleanup_redirects_guests(): void
    {
        $this->get('/admin/db-cleanup')
            ->assertRedirect(route('start', ['destination' => '/platform/admin/db-cleanup']));
    }

    public function test_db_cleanup_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/db-cleanup')
            ->assertRedirect(route('start'));
    }

    public function test_db_cleanup_lists_duplicate_playerteams(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminDbCleanupService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_config.png',
                        'name' => 'DB Cleanup',
                        'link' => '/platform/admin/db-cleanup',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_game' => null,
                'duplicate_playerteam_groups' => [
                    [
                        'player_id' => 12,
                        'team_id' => 3,
                        'player_fname' => 'Marko',
                        'player_lname' => 'Arnautovic',
                        'team_name' => 'Rapid',
                        'entry_count' => 2,
                        'entries' => [
                            [
                                'playerteam_id' => 44,
                                'playerteam_status' => 1,
                                'playerteam_player_price' => 8,
                                'playerteam_player_position' => 's',
                                'playerteam_date_transfer' => '2008-01-01',
                                'playerteam_player_picture' => '44.jpg',
                            ],
                            [
                                'playerteam_id' => 99,
                                'playerteam_status' => 0,
                                'playerteam_player_price' => 5,
                                'playerteam_player_position' => 'm',
                                'playerteam_date_transfer' => '2010-06-01',
                                'playerteam_player_picture' => '',
                            ],
                        ],
                    ],
                ],
                'duplicate_playerteam_group_count' => 1,
                'duplicate_playerteam_entry_count' => 2,
                'players_without_playerteam' => [
                    [
                        'player_id' => 501,
                        'player_fname' => 'Orphan',
                        'player_lname' => 'NoTeam',
                        'player_nationality' => 'AUT',
                        'player_status' => 1,
                        'player_foreign_id' => '',
                    ],
                ],
                'players_without_playerteam_count' => 1,
                'players_without_playerstats' => [
                    [
                        'player_id' => 502,
                        'player_fname' => 'Bench',
                        'player_lname' => 'NoStats',
                        'player_nationality' => 'GER',
                        'player_status' => 0,
                        'player_foreign_id' => '1/bench',
                    ],
                ],
                'players_without_playerstats_count' => 1,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/db-cleanup')
            ->assertOk()
            ->assertSee('DB Cleanup', false)
            ->assertSee('Doppelte Kader-Einträge', false)
            ->assertSee('Arnautovic', false)
            ->assertSee('Rapid', false)
            ->assertSee('#44', false)
            ->assertSee('#99', false)
            ->assertSee('playerteam_player_id', false)
            ->assertSee('Spieler ohne Kader-Zuordnung', false)
            ->assertSee('NoTeam', false)
            ->assertSee('Spieler ohne Spielstatistiken', false)
            ->assertSee('ffb_userteam', false)
            ->assertSee('NoStats', false);
    }

    public function test_db_cleanup_shows_empty_state(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminDbCleanupService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [],
                'selected_game' => null,
                'duplicate_playerteam_groups' => [],
                'duplicate_playerteam_group_count' => 0,
                'duplicate_playerteam_entry_count' => 0,
                'players_without_playerteam' => [],
                'players_without_playerteam_count' => 0,
                'players_without_playerstats' => [],
                'players_without_playerstats_count' => 0,
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/db-cleanup')
            ->assertOk()
            ->assertSee('Keine doppelten Spieler–Team-Zuordnungen gefunden.', false)
            ->assertSee('Alle Spieler sind mindestens einem Team zugeordnet.', false)
            ->assertSee('Jeder Spieler hat mindestens eine Spielstatistik.', false);
    }
}
