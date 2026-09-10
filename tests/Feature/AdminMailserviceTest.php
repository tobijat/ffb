<?php

namespace Tests\Feature;

use App\Services\AdminMailserviceService;
use App\Services\FfbAdminAccess;
use App\Services\FfbAuth;
use Tests\TestCase;

class AdminMailserviceTest extends TestCase
{
    public function test_mailservice_redirects_guests(): void
    {
        $this->get('/admin/mailservice')
            ->assertRedirect(route('start', ['destination' => '/admin/mailservice']));
    }

    public function test_mailservice_redirects_non_admins(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->with(544)->andReturn(false);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/mailservice')
            ->assertRedirect(route('start'));
    }

    public function test_mailservice_page_renders(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('pagePayload')->once()->with(544)->andReturn([
                'user' => [
                    'user_id' => 544,
                    'user_nickname' => 'adminuser',
                    'photo_url' => '/images/ffb/profiles/photo/profile_na.png',
                    'is_ffb_admin' => true,
                ],
                'navigation' => [
                    [
                        'symbol' => 'nav_mail.png',
                        'name' => 'Mailservice',
                        'link' => '/admin/mailservice',
                        'style' => 'big',
                        'image_dir' => 'images/admin/navigation/',
                    ],
                ],
                'selected_league_id' => 7,
                'selected_league' => [
                    'league_id' => 7,
                    'league_title' => 'Bundesliga Test',
                    'symbol_url' => '/images/ffb/games/na.png',
                ],
                'leagues' => [
                    ['league_id' => 7, 'league_title' => 'Bundesliga Test'],
                ],
                'mails' => [
                    [
                        'mail_id' => 99,
                        'mail_sender' => 'admin/adminuser',
                        'mail_date' => '2024-01-02 12:00:00',
                        'mail_subject' => 'Test Subject',
                        'mail_text' => 'Hello',
                        'mail_num_reciepients' => 3,
                        'mail_to' => '1,2,3',
                        'mail_criteria' => 'info',
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->get('/admin/mailservice')
            ->assertOk()
            ->assertSee('Mailservice', false)
            ->assertSee('Get Users', false)
            ->assertSee('never added a lineup', false)
            ->assertSee('Send Mail', false)
            ->assertSee('Bundesliga Test', false)
            ->assertSee('Test Subject', false)
            ->assertSee('3 Empfänger', false)
            ->assertSee('admin-mailservice.js', false);
    }

    public function test_matchrounds_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('matchroundsForGame')->once()->with(7)->andReturn([
                ['matchround_id' => 11, 'matchround_title' => 'Runde 1'],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/mailservice/matchrounds?league_id=7')
            ->assertOk()
            ->assertJson([
                'numResults' => 1,
                'matchrounds' => [
                    ['matchround_id' => 11, 'matchround_title' => 'Runde 1'],
                ],
            ]);
    }

    public function test_users_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('users')->once()->with([
                'league_id' => '7',
                'matchround_id' => 0,
                'mailservice' => 'info',
                'userstatus' => 'active',
            ])->andReturn([
                [
                    'user_id' => 9,
                    'user_nickname' => 'player1',
                    'user_email' => 'p1@example.com',
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/mailservice/users?league_id=7&userstatus=active&mailservice=info')
            ->assertOk()
            ->assertJsonPath('numResults', 1)
            ->assertJsonPath('users.0.user_nickname', 'player1');
    }

    public function test_mail_by_id_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('mailById')->once()->with(99)->andReturn([
                'mail' => [
                    'mail_id' => 99,
                    'mail_sender' => 'admin/x',
                    'mail_date' => '2024-01-02 12:00:00',
                    'mail_subject' => 'Reload me',
                    'mail_text' => 'Body',
                    'mail_num_reciepients' => 1,
                    'mail_criteria' => 'reminder',
                ],
                'users' => [
                    [
                        'user_id' => 9,
                        'user_nickname' => 'player1',
                        'user_email' => 'p1@example.com',
                    ],
                ],
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->getJson('/admin/mailservice/mails/99')
            ->assertOk()
            ->assertJsonPath('mail.mail_subject', 'Reload me')
            ->assertJsonPath('users.0.user_id', 9);
    }

    public function test_send_endpoint(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->withArgs(function ($userIds, $subject, $text, $type, $adminUserId, $host) {
                    return $userIds === [9, 10]
                        && $subject === 'Hello'
                        && $text === 'World'
                        && $type === 'info'
                        && $adminUserId === 544
                        && is_string($host);
                })
                ->andReturn([
                    'ok' => true,
                    'message' => 'The email was sent to 2 Users.',
                    'num_send' => 2,
                ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/mailservice/send', [
                'user_ids' => [9, 10],
                'subject' => 'Hello',
                'text' => 'World',
                'type' => 'info',
            ])
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 200,
                'answer' => 'The email was sent to 2 Users.',
                'num_send' => 2,
            ]);
    }

    public function test_send_endpoint_failure(): void
    {
        $this->mock(FfbAdminAccess::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $this->mock(AdminMailserviceService::class, function ($mock) {
            $mock->shouldReceive('send')->once()->andReturn([
                'ok' => false,
                'message' => 'The email could not be send to any user.',
            ]);
        });

        $this->withSession([FfbAuth::SESSION_USER_ID => 544])
            ->postJson('/admin/mailservice/send', [
                'user_ids' => [],
                'subject' => '',
                'text' => '',
                'type' => 'force',
            ])
            ->assertStatus(500)
            ->assertJsonPath('ok', false);
    }
}
