<?php

declare(strict_types=1);

namespace FFB\Tests\Support;

use PDO;

/**
 * Creates an isolated game + rounds + matches for contract tests,
 * then removes that game-scoped data on teardown.
 * Reuses existing teams/players; keeps the permanent test user.
 */
final class FixtureManager
{
    public const GAME_MARKER = 'ffb_contract_test_fixture';
    public const STATE_FILE = '.ffb_test_fixture.json';

    private PDO $pdo;
    private string $statePath;
    private string $nickname;
    private string $passwordPlain;

    public function __construct(?PDO $pdo = null)
    {
        $host = getenv('FFB_DB_HOST') ?: '127.0.0.1';
        $name = getenv('FFB_DB_NAME') ?: '';
        $user = getenv('FFB_DB_USER') ?: '';
        $pass = getenv('FFB_DB_PASSWORD');
        $charset = getenv('FFB_DB_CHARSET') ?: 'utf8mb4';

        $dsn = getenv('FFB_DB_DSN') ?: '';
        if ($dsn === '' && $name !== '') {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=' . $charset;
        }
        if ($dsn === '' || $user === '' || $pass === false) {
            throw new \RuntimeException(
                'Database env missing. Copy .env.example to .env and set FFB_DB_* values.'
            );
        }

        $this->pdo = $pdo ?: new PDO($dsn, $user, (string) $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $this->statePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . self::STATE_FILE;
        $this->nickname = getenv('FFB_TEST_USER') ?: 'ffb_contract_tester';
        $this->passwordPlain = getenv('FFB_TEST_PASSWORD') ?: 'testpass123';
    }

    /**
     * @return array<string, int|string>
     */
    public function setup(): array
    {
        $this->teardownAllContractGames();
        $userId = $this->ensureTestUser();
        $teamIds = $this->pickTeamsWithPlayers(10);
        if (count($teamIds) < 4) {
            throw new \RuntimeException('Need at least 4 teams with 11+ active players for fixtures');
        }

        $now = time();
        $gameId = $this->insertGame();
        $this->insertOptions($gameId);
        $pastRoundId = $this->insertMatchround(
            $gameId,
            'Contract Past Round',
            date('Y-m-d H:i:s', $now - 14 * 86400),
            date('Y-m-d H:i:s', $now - 7 * 86400)
        );
        $futureRoundId = $this->insertMatchround(
            $gameId,
            'Contract Future Round',
            date('Y-m-d H:i:s', $now + 7 * 86400),
            date('Y-m-d H:i:s', $now + 14 * 86400)
        );

        $pastMatchId = $this->insertMatch(
            $pastRoundId,
            $teamIds[0],
            $teamIds[1],
            date('Y-m-d H:i:s', $now - 10 * 86400),
            '1',
            '0'
        );
        $this->insertMatch(
            $pastRoundId,
            $teamIds[2],
            $teamIds[3],
            date('Y-m-d H:i:s', $now - 9 * 86400),
            '2',
            '2'
        );

        $futureMatches = [];
        for ($i = 0; $i < 4; $i++) {
            $home = $teamIds[$i * 2];
            $guest = $teamIds[$i * 2 + 1];
            $futureMatches[] = $this->insertMatch(
                $futureRoundId,
                $home,
                $guest,
                date('Y-m-d H:i:s', $now + (8 + $i) * 86400),
                '',
                ''
            );
        }

        $this->pointUserAtGame($userId, $gameId);
        $this->ensureWebAdmin($userId);
        $this->ensureFfbAdmin($userId, $gameId);

        $player = $this->pickPlayerOnTeam($teamIds[0]);
        $polls = $this->insertTestPolls($gameId);

        $state = [
            'user_id' => $userId,
            'game_id' => $gameId,
            'matchround_id' => $futureRoundId,
            'past_matchround_id' => $pastRoundId,
            'team_id' => $teamIds[0],
            'match_id' => $pastMatchId,
            'future_match_id' => $futureMatches[0],
            'playerteam_id' => $player['playerteam_id'],
            'player_id' => $player['player_id'],
            'select_poll_id' => $polls['select_poll_id'],
            'select_poll_answer_id' => $polls['select_poll_answer_id'],
            'text_poll_id' => $polls['text_poll_id'],
            'text_poll_answer_id' => $polls['text_poll_answer_id'],
            'award_id' => $this->firstAwardId(),
            'created_at' => date('c'),
        ];

        file_put_contents($this->statePath, json_encode($state, JSON_PRETTY_PRINT));
        file_put_contents(dirname(__DIR__) . '/.ffb_test_user_id', (string)$userId);

        return $state;
    }

    public function teardown(): void
    {
        $this->teardownAllContractGames();
        $this->resetTesterSelectedGame();
        if (is_file($this->statePath)) {
            @unlink($this->statePath);
        }
    }

    /**
     * Apply fixture IDs into process env for PHPUnit tests.
     *
     * @param array<string, int|string> $state
     */
    public static function applyEnv(array $state): void
    {
        $map = [
            'user_id' => 'FFB_TEST_USER_ID',
            'game_id' => 'FFB_TEST_GAME_ID',
            'matchround_id' => 'FFB_TEST_MATCHROUND_ID',
            'past_matchround_id' => 'FFB_TEST_PAST_MATCHROUND_ID',
            'team_id' => 'FFB_TEST_TEAM_ID',
            'match_id' => 'FFB_TEST_MATCH_ID',
            'playerteam_id' => 'FFB_TEST_PLAYERTEAM_ID',
            'player_id' => 'FFB_TEST_PLAYER_ID',
            'select_poll_id' => 'FFB_TEST_SELECT_POLL_ID',
            'select_poll_answer_id' => 'FFB_TEST_SELECT_POLL_ANSWER_ID',
            'text_poll_id' => 'FFB_TEST_TEXT_POLL_ID',
            'text_poll_answer_id' => 'FFB_TEST_TEXT_POLL_ANSWER_ID',
            'award_id' => 'FFB_TEST_AWARD_ID',
        ];
        foreach ($map as $key => $env) {
            if (!isset($state[$key])) {
                continue;
            }
            $value = (string)$state[$key];
            putenv($env . '=' . $value);
            $_ENV[$env] = $value;
        }
    }

    public function teardownAllContractGames(): void
    {
        $games = $this->pdo->query(
            "SELECT game_id FROM ffb_game WHERE game_description = " . $this->pdo->quote(self::GAME_MARKER)
        )->fetchAll(PDO::FETCH_COLUMN);

        foreach ($games as $gameId) {
            $this->deleteGameWorld((int)$gameId);
        }
    }

    private function deleteGameWorld(int $gameId): void
    {
        $roundIds = $this->pdo->prepare('SELECT matchround_id FROM ffb_matchround WHERE matchround_game_id = ?');
        $roundIds->execute([$gameId]);
        $rounds = array_map('intval', $roundIds->fetchAll(PDO::FETCH_COLUMN));

        if ($rounds) {
            $in = implode(',', $rounds);
            $matchIds = $this->pdo->query("SELECT match_id FROM ffb_match WHERE match_round IN ($in)")
                ->fetchAll(PDO::FETCH_COLUMN);
            if ($matchIds) {
                $min = implode(',', array_map('intval', $matchIds));
                $this->pdo->exec("DELETE FROM ffb_goal WHERE goal_match_id IN ($min)");
                $this->pdo->exec("DELETE FROM ffb_psgoal WHERE psgoal_match_id IN ($min)");
                $this->pdo->exec("DELETE FROM ffb_playerstats WHERE playerstats_match_id IN ($min)");
                $this->pdo->exec("DELETE FROM ffb_match WHERE match_id IN ($min)");
            }
            $this->pdo->exec("DELETE FROM ffb_playerstats WHERE playerstats_matchround_id IN ($in)");
            $this->pdo->exec("DELETE FROM ffb_playerprice WHERE playerprice_matchround_id IN ($in)");
            $this->pdo->exec("DELETE FROM ffb_userteam WHERE userteam_matchround_id IN ($in)");
            $this->pdo->exec("DELETE FROM ffb_matchround WHERE matchround_id IN ($in)");
        }

        $this->pdo->exec('DELETE FROM ffb_userscore WHERE userscore_game_id = ' . $gameId);
        $this->pdo->exec('DELETE FROM ffb_news WHERE news_game_id = ' . $gameId);
        $this->pdo->exec('DELETE FROM ffb_options WHERE options_game_id = ' . $gameId);
        $this->pdo->exec('DELETE FROM ffb_admin WHERE admin_game_id = ' . $gameId);

        $pollIds = $this->pdo->query('SELECT poll_id FROM ffb_poll WHERE poll_game_id = ' . $gameId)
            ->fetchAll(PDO::FETCH_COLUMN);
        if ($pollIds) {
            $pin = implode(',', array_map('intval', $pollIds));
            $this->pdo->exec("DELETE FROM ffb_poll_result WHERE poll_result_poll_id IN ($pin)");
            $this->pdo->exec("DELETE FROM ffb_poll_answer WHERE poll_answer_poll_id IN ($pin)");
            $this->pdo->exec("DELETE FROM ffb_poll WHERE poll_id IN ($pin)");
        }

        $this->pdo->exec('DELETE FROM ffb_game WHERE game_id = ' . $gameId);
    }

    private function ensureTestUser(): int
    {
        // Intentionally MD5 so contract tests still exercise legacy login + rehash-on-login.
        $passwordHash = md5($this->passwordPlain);
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('SELECT user_id FROM web_user WHERE user_nickname = ? LIMIT 1');
        $stmt->execute([$this->nickname]);
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            $ins = $this->pdo->prepare(
                'INSERT INTO web_user (
                    user_nickname, user_password, user_email, user_fname, user_lname,
                    user_gender, user_status, user_admin, user_nationality, user_date_birth,
                    user_ip, user_lip, user_date_register, user_date_llogin, user_date_laction,
                    user_activation_code, user_mailservice
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
            );
            $ins->execute([
                $this->nickname,
                $passwordHash,
                'ffb_contract_tester@example.invalid',
                'Contract',
                'Tester',
                'm',
                'active',
                0,
                'AT',
                '1990-01-01 00:00:00',
                '127.0.0.1',
                '127.0.0.1',
                $now,
                $now,
                $now,
                'contract-test-activation',
                'none',
            ]);
            $userId = (int)$this->pdo->lastInsertId();
        } else {
            $userId = (int)$userId;
            $this->pdo->prepare(
                'UPDATE web_user SET user_password = ?, user_status = ?, user_date_laction = ? WHERE user_id = ?'
            )->execute([$passwordHash, 'active', $now, $userId]);
        }

        $perms = $this->pdo->prepare('SELECT user_id FROM web_user_permissions WHERE user_id = ?');
        $perms->execute([$userId]);
        if (!$perms->fetchColumn()) {
            $this->pdo->prepare(
                'INSERT INTO web_user_permissions (
                    user_id,
                    user_permissions_ffb_mailservice_reminder,
                    user_permissions_ffb_mailservice_info,
                    user_permissions_ffb_visible_profile,
                    user_permissions_pictory_visible_profile
                ) VALUES (?, ?, ?, ?, ?)'
            )->execute([$userId, 'yes', 'yes', 1, 0]);
        }

        return $userId;
    }

    private function pointUserAtGame(int $userId, int $gameId): void
    {
        $now = date('Y-m-d H:i:s');
        $details = $this->pdo->prepare('SELECT user_id FROM web_user_details WHERE user_id = ?');
        $details->execute([$userId]);
        if (!$details->fetchColumn()) {
            $this->pdo->prepare(
                'INSERT INTO web_user_details (
                    user_id, user_details_avatar, user_details_photo, user_details_website,
                    user_details_zip, user_details_street, user_details_city, user_details_phone,
                    user_details_ffb_favourite_team, user_details_ffb_own_team, user_details_ffb_own_player,
                    user_details_ffb_selected_game, user_details_last_update
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, ?, ?)'
            )->execute([
                $userId,
                'avatar_default.png',
                'photo_default.png',
                '',
                '',
                '',
                '',
                '',
                $gameId,
                $now,
            ]);
        } else {
            $this->pdo->prepare(
                'UPDATE web_user_details SET user_details_ffb_selected_game = ?, user_details_last_update = ? WHERE user_id = ?'
            )->execute([$gameId, $now, $userId]);
        }
    }

    private function ensureWebAdmin(int $userId): void
    {
        $admin = $this->pdo->prepare(
            'SELECT admin_id FROM web_admin WHERE admin_user_id = ? AND admin_section = ? LIMIT 1'
        );
        $admin->execute([$userId, 'ffb']);
        if (!$admin->fetchColumn()) {
            $this->pdo->prepare(
                'INSERT INTO web_admin (admin_user_id, admin_section, admin_level, admin_status)
                 VALUES (?, ?, ?, ?)'
            )->execute([$userId, 'ffb', 100, 'active']);
        } else {
            $this->pdo->prepare(
                'UPDATE web_admin SET admin_status = ?, admin_level = ? WHERE admin_user_id = ? AND admin_section = ?'
            )->execute(['active', 100, $userId, 'ffb']);
        }
    }

    private function ensureFfbAdmin(int $userId, int $gameId): void
    {
        $ffbAdmin = $this->pdo->prepare(
            'SELECT admin_id FROM ffb_admin WHERE admin_user_id = ? AND admin_game_id = ? LIMIT 1'
        );
        $ffbAdmin->execute([$userId, $gameId]);
        if (!$ffbAdmin->fetchColumn()) {
            $this->pdo->prepare(
                'INSERT INTO ffb_admin (admin_user_id, admin_game_id) VALUES (?, ?)'
            )->execute([$userId, $gameId]);
        }
    }

    private function insertGame(): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ffb_game (
                game_title, game_visible, game_archive, game_countdown, game_status,
                game_description, game_symbol
            ) VALUES (?, 1, 0, 0, 1, ?, ?)'
        );
        $stmt->execute([
            'FFB Contract Test ' . date('Y-m-d H:i:s'),
            self::GAME_MARKER,
            'game_symbol_na.png',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertOptions(int $gameId): void
    {
        $src = $this->pdo->query(
            'SELECT * FROM ffb_options ORDER BY options_id DESC LIMIT 1'
        )->fetch(PDO::FETCH_ASSOC);

        if ($src) {
            unset($src['options_id']);
            $src['options_game_id'] = $gameId;
            $cols = array_keys($src);
            $placeholders = implode(',', array_fill(0, count($cols), '?'));
            $sql = 'INSERT INTO ffb_options (' . implode(',', $cols) . ') VALUES (' . $placeholders . ')';
            $this->pdo->prepare($sql)->execute(array_values($src));
            return;
        }

        // Fallback defaults if the DB has no options rows at all.
        $this->pdo->prepare(
            'INSERT INTO ffb_options (
                options_game_id,
                options_score_minutes, options_score_minutes_treshold, options_score_minutes_gt,
                options_score_minutes_lt, options_score_minutes_lt30,
                options_score_goals_g, options_score_goals_d, options_score_goals_m, options_score_goals_s,
                options_score_assists,
                options_score_no_oppgoals_g, options_score_no_oppgoals_d, options_score_no_oppgoals_m,
                options_score_oppgoals_g, options_score_oppgoals_d,
                options_score_owngoals, options_score_card_y, options_score_card_r, options_score_card_yr,
                options_score_penalty_saved, options_score_penalty_lost,
                options_score_penaltyshootout_save, options_score_penaltyshootout_lost, options_score_penaltyshootout_hit,
                options_score_high_loss, options_score_high_win, options_score_high_win_loss_treshold,
                options_status_error, options_status_error_validation, options_status_success,
                options_status_success_insert, options_status_success_update, options_status_success_delete,
                options_lineup_max_players, options_lineup_max_credits, options_lineup_max_players_team,
                options_lineup_min_g, options_lineup_min_d, options_lineup_min_m, options_lineup_min_s,
                options_lineup_max_g, options_lineup_max_d, options_lineup_max_m, options_lineup_max_s,
                options_game_rankmode, options_game_pricemode, options_game_pointsmode, options_game_wcpoints,
                options_game_remind_hours_before
            ) VALUES (
                ?, 1, 60, 1, 0, 0, 6, 5, 4, 4, 3, 4, 2, 0, -1, -1, -2, -1, -3, -3, 3, -2, 2, -1, 1, -2, 2, 3,
                500, 501, 200, 201, 202, 203, 11, 100, 3, 1, 3, 3, 1, 1, 5, 5, 3,
                \'wc\', \'dynamic\', \'new\', \'new\', 0
            )'
        )->execute([$gameId]);
    }

    private function insertMatchround(int $gameId, string $title, string $start, string $end): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ffb_matchround (
                matchround_game_id, matchround_title, matchround_startdate, matchround_enddate,
                matchround_status, matchround_credits, matchround_max_players_from_team
            ) VALUES (?, ?, ?, ?, 1, 100, 3)'
        );
        $stmt->execute([$gameId, $title, $start, $end]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertMatch(
        int $roundId,
        int $homeTeamId,
        int $guestTeamId,
        string $date,
        string $homeScore,
        string $guestScore
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ffb_match (
                match_round, match_hometeam_id, match_guestteam_id,
                match_homescore, match_guestscore, match_homescore_penalty, match_guestscore_penalty,
                match_date, match_minutes, match_status, match_url
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $roundId,
            $homeTeamId,
            $guestTeamId,
            $homeScore,
            $guestScore,
            '',
            '',
            $date,
            $homeScore === '' ? 0 : 90,
            '',
            '',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @return list<int>
     */
    private function pickTeamsWithPlayers(int $needed): array
    {
        $sql = 'SELECT playerteam_team_id AS team_id, COUNT(*) AS cnt
                FROM ffb_playerteam
                WHERE playerteam_status = 1
                GROUP BY playerteam_team_id
                HAVING cnt >= 11
                ORDER BY cnt DESC
                LIMIT ' . (int)$needed;
        return array_map('intval', $this->pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * @return array{playerteam_id:int, player_id:int}
     */
    private function pickPlayerOnTeam(int $teamId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT playerteam_id, playerteam_player_id
             FROM ffb_playerteam
             WHERE playerteam_team_id = ? AND playerteam_status = 1
             ORDER BY playerteam_id ASC
             LIMIT 1'
        );
        $stmt->execute([$teamId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new \RuntimeException('No active playerteam found for team_id=' . $teamId);
        }
        return [
            'playerteam_id' => (int)$row['playerteam_id'],
            'player_id' => (int)$row['playerteam_player_id'],
        ];
    }

    /**
     * @return array{
     *   select_poll_id:int,
     *   select_poll_answer_id:int,
     *   text_poll_id:int,
     *   text_poll_answer_id:int
     * }
     */
    private function insertTestPolls(int $gameId): array
    {
        $now = time();
        $start = date('Y-m-d H:i:s', $now - 3600);
        $end = date('Y-m-d H:i:s', $now + 7 * 86400);

        $selectPollId = $this->insertPoll(
            $gameId,
            'Contract Select Poll',
            'select',
            'right',
            $start,
            $end
        );
        $this->insertPollAnswer($selectPollId, 'Contract Answer A');
        $selectAnswerId = $this->insertPollAnswer($selectPollId, 'Contract Answer B');

        $textPollId = $this->insertPoll(
            $gameId,
            'Contract Text Poll',
            'text',
            'right',
            $start,
            $end
        );
        $textAnswerId = $this->insertPollAnswer(
            $textPollId,
            'Contract text prompt: what do you think?'
        );

        return [
            'select_poll_id' => $selectPollId,
            'select_poll_answer_id' => $selectAnswerId,
            'text_poll_id' => $textPollId,
            'text_poll_answer_id' => $textAnswerId,
        ];
    }

    private function insertPoll(
        int $gameId,
        string $title,
        string $type,
        string $location,
        string $start,
        string $end
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ffb_poll (
                poll_title, poll_start, poll_end, poll_game_id, poll_location, poll_type, poll_visible
            ) VALUES (?, ?, ?, ?, ?, ?, 1)'
        );
        $stmt->execute([$title, $start, $end, $gameId, $location, $type]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertPollAnswer(int $pollId, string $title): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ffb_poll_answer (poll_answer_poll_id, poll_answer_title, poll_answer_count)
             VALUES (?, ?, 0)'
        );
        $stmt->execute([$pollId, $title]);
        return (int)$this->pdo->lastInsertId();
    }

    private function firstAwardId(): int
    {
        $id = $this->pdo->query('SELECT user_award_id FROM ffb_user_award ORDER BY user_award_id ASC LIMIT 1')
            ->fetchColumn();
        return $id ? (int)$id : 1;
    }

    private function resetTesterSelectedGame(): void
    {
        $stmt = $this->pdo->prepare('SELECT user_id FROM web_user WHERE user_nickname = ? LIMIT 1');
        $stmt->execute([$this->nickname]);
        $userId = $stmt->fetchColumn();
        if (!$userId) {
            return;
        }

        $fallback = $this->pdo->query(
            "SELECT game_id FROM ffb_game
             WHERE COALESCE(game_description, '') <> " . $this->pdo->quote(self::GAME_MARKER) . "
             ORDER BY game_id DESC
             LIMIT 1"
        )->fetchColumn();
        if (!$fallback) {
            return;
        }

        $this->pdo->prepare(
            'UPDATE web_user_details SET user_details_ffb_selected_game = ?, user_details_last_update = ? WHERE user_id = ?'
        )->execute([(int)$fallback, date('Y-m-d H:i:s'), (int)$userId]);
    }
}
