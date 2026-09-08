<?php

namespace Tests\Unit;

use App\Services\WeltfussballMatchScraper;
use App\Services\WeltfussballProxyService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeltfussballMatchScraperTest extends TestCase
{
    public function test_parse_lineup_html(): void
    {
        $html = file_get_contents(base_path('tests/Fixtures/weltfussball_lineup_sample.html'));
        $this->assertNotFalse($html);

        $parsed = (new WeltfussballMatchScraper(new WeltfussballProxyService()))->parse($html);

        $this->assertSame(120, $parsed['match_minutes']);
        $this->assertStringContainsString('1:0', $parsed['result_text']);
        $this->assertStringContainsString('n.V.', $parsed['result_text']);

        $homeNames = array_column($parsed['home'], 'player_name');
        $this->assertContains('Rui Patrício', $homeNames);
        $this->assertContains('Cristiano Ronaldo', $homeNames);
        $this->assertContains('Éder', $homeNames);
        $this->assertContains('João Moutinho', $homeNames);
        $this->assertContains('Ricardo Quaresma', $homeNames);
        $this->assertCount(11, array_filter(
            $parsed['home'],
            static fn (array $p): bool => (int) ($p['player_change_in'] ?? 0) === 1
        ));

        $rui = collect($parsed['home'])->firstWhere('player_name', 'Rui Patrício');
        $this->assertNotNull($rui);
        $this->assertSame('Y', $rui['player_cards']);
        $this->assertSame(1, $rui['player_change_in']);
        $this->assertSame(120, $rui['player_change_out']);
        $this->assertSame(120, $rui['player_minutes']);

        $ronaldo = collect($parsed['home'])->firstWhere('player_name', 'Cristiano Ronaldo');
        $this->assertNotNull($ronaldo);
        $this->assertSame(1, $ronaldo['player_change_in']);
        $this->assertSame(25, $ronaldo['player_change_out']);
        $this->assertSame(25, $ronaldo['player_minutes']);
        $this->assertSame(0, $ronaldo['player_num_goals']);

        $eder = collect($parsed['home'])->firstWhere('player_name', 'Éder');
        $this->assertNotNull($eder);
        $this->assertSame(79, $eder['player_change_in']);
        $this->assertSame(120, $eder['player_change_out']);
        $this->assertSame(1, $eder['player_num_goals']);
        $this->assertSame('109', $eder['player_goal']);
        $this->assertSame(42, $eder['player_minutes']); // 120 - 79 + 1

        $moutinho = collect($parsed['home'])->firstWhere('player_name', 'João Moutinho');
        $this->assertNotNull($moutinho);
        $this->assertSame(1, $moutinho['player_num_assists']);
        $this->assertSame(66, $moutinho['player_change_in']);
        $this->assertSame(120, $moutinho['player_change_out']);

        $guestNames = array_column($parsed['guest'], 'player_name');
        $this->assertContains('Hugo Lloris', $guestNames);
        $this->assertContains('Antoine Griezmann', $guestNames);
        $this->assertContains('Kingsley Coman', $guestNames);

        $coman = collect($parsed['guest'])->firstWhere('player_name', 'Kingsley Coman');
        $this->assertNotNull($coman);
        $this->assertSame(58, $coman['player_change_in']);
        $this->assertSame(120, $coman['player_change_out']);
    }

    public function test_parse_rejects_challenge_pages(): void
    {
        $this->expectException(\App\Exceptions\CloudflareChallengeException::class);
        (new WeltfussballMatchScraper(new WeltfussballProxyService()))
            ->parse('<html>Just a moment... cf-browser-verification</html>');
    }

    public function test_parse_rejects_legacy_standard_tabelle(): void
    {
        $this->expectException(\RuntimeException::class);
        (new WeltfussballMatchScraper(new WeltfussballProxyService()))
            ->parse('<html><table class="standard_tabelle">Tore</table><div class="resultat">1:0</div></html>');
    }

    public function test_parse_ignores_cloudflare_assets_on_real_pages(): void
    {
        $html = file_get_contents(base_path('tests/Fixtures/weltfussball_lineup_sample.html'));
        $this->assertNotFalse($html);
        $html .= '<script src="/cdn-cgi/challenge-platform/scripts/jsd/main.js"></script>';

        $parsed = (new WeltfussballMatchScraper(new WeltfussballProxyService()))->parse($html);
        $this->assertSame(120, $parsed['match_minutes']);
    }

    public function test_fetch_and_parse_works_without_frame_when_http_ok(): void
    {
        $html = file_get_contents(base_path('tests/Fixtures/weltfussball_lineup_sample.html'));
        $this->assertNotFalse($html);

        $matchUrl = 'https://www.weltfussball.at/spielbericht/example/';

        Http::fake([
            'https://www.weltfussball.at/' => Http::response('<html>home</html>', 200),
            $matchUrl => Http::response($html, 200),
        ]);

        $parsed = (new WeltfussballMatchScraper(new WeltfussballProxyService()))
            ->fetchAndParse($matchUrl);

        $this->assertSame(120, $parsed['match_minutes']);
        $this->assertContains('Cristiano Ronaldo', array_column($parsed['home'], 'player_name'));
        Http::assertSentCount(2);
    }
}
