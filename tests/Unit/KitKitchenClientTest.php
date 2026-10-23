<?php

namespace Tests\Unit;

use App\Support\KitKitchenClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KitKitchenClientTest extends TestCase
{
    public function test_fetch_returns_seed_and_svg(): void
    {
        config(['ffb.kit_kitchen_random_url' => 'https://kit.test/random.php']);

        Http::fake([
            'kit.test/*' => Http::response(
                '<svg xmlns="http://www.w3.org/2000/svg"><rect/></svg>',
                200,
                [
                    'Content-Type' => 'image/svg+xml',
                    'X-Kit-Seed' => '123456789',
                ]
            ),
        ]);

        $result = (new KitKitchenClient)->fetch();

        $this->assertNotNull($result);
        $this->assertSame('123456789', $result['seed']);
        $this->assertStringContainsString('<svg', $result['svg']);
    }

    public function test_fetch_passes_seed_and_options_query(): void
    {
        config(['ffb.kit_kitchen_random_url' => 'https://kit.test/random.php']);

        Http::fake([
            'kit.test/*' => Http::response(
                '<svg xmlns="http://www.w3.org/2000/svg"><rect/></svg>',
                200,
                ['X-Kit-Seed' => '42']
            ),
        ]);

        $result = (new KitKitchenClient)->fetch([
            'seed' => '42',
            'stars' => 3,
            'number' => '10',
            'maker' => 'adidas',
            'backside' => true,
            'text' => 'Captain',
            'mcolor' => '#ff0000',
            'ncolor' => '0f0',
            'tcolor' => '00ff00',
            'scolor' => '#ffd700',
        ]);
        $this->assertNotNull($result);
        $this->assertSame('42', $result['seed']);

        Http::assertSent(function ($request) {
            $url = $request->url();

            return str_contains($url, 'seed=42')
                && str_contains($url, 'stars=3')
                && str_contains($url, 'number=10')
                && str_contains($url, 'maker=adidas')
                && str_contains($url, 'backside=1')
                && str_contains($url, 'text=Captain')
                && str_contains($url, 'mcolor=ff0000')
                && str_contains($url, 'ncolor=00ff00')
                && str_contains($url, 'tcolor=00ff00')
                && str_contains($url, 'scolor=ffd700');
        });
    }

    public function test_normalize_color(): void
    {
        $this->assertSame('#ff0000', KitKitchenClient::normalizeColor('#FF0000'));
        $this->assertSame('#00ff00', KitKitchenClient::normalizeColor('0f0'));
        $this->assertSame('#aabbcc', KitKitchenClient::normalizeColor('aabbcc'));
        $this->assertNull(KitKitchenClient::normalizeColor(''));
        $this->assertNull(KitKitchenClient::normalizeColor('red'));
        $this->assertNull(KitKitchenClient::normalizeColor('#gg0000'));
    }

    public function test_build_query_omits_invalid_colors(): void
    {
        $client = new KitKitchenClient;
        $query = $client->buildQuery([
            'mcolor' => 'nope',
            'ncolor' => '#12345',
            'tcolor' => '#abcdef',
        ]);

        $this->assertArrayNotHasKey('mcolor', $query);
        $this->assertArrayNotHasKey('ncolor', $query);
        $this->assertSame('abcdef', $query['tcolor']);
    }

    public function test_build_query_accepts_one_digit_number_and_limits_text(): void
    {
        $client = new KitKitchenClient;
        $query = $client->buildQuery([
            'stars' => 9,
            'number' => '7',
            'maker' => 'nope',
            'backside' => true,
            'text' => 'ABCDEFGHIJKLMNOPQRST',
        ]);

        $this->assertSame(9, $query['stars']);
        $this->assertSame('7', $query['number']);
        $this->assertArrayNotHasKey('maker', $query);
        $this->assertSame(1, $query['backside']);
        $this->assertSame(16, strlen($query['text']));
    }

    public function test_build_query_omits_invalid_number(): void
    {
        $client = new KitKitchenClient;
        $query = $client->buildQuery([
            'number' => '100',
        ]);

        $this->assertArrayNotHasKey('number', $query);
    }

    public function test_normalize_maker(): void
    {
        $this->assertSame('capelli', KitKitchenClient::normalizeMaker('Capelli'));
        $this->assertSame('sudu', KitKitchenClient::normalizeMaker('sudu'));
        $this->assertNull(KitKitchenClient::normalizeMaker(''));
        $this->assertNull(KitKitchenClient::normalizeMaker('unknown'));
    }

    public function test_fetch_posts_svg_body(): void
    {
        config(['ffb.kit_kitchen_random_url' => 'https://kit.test/random.php']);

        Http::fake([
            'kit.test/*' => Http::response(
                '<svg xmlns="http://www.w3.org/2000/svg"><rect id="imported"/></svg>',
                200,
                ['X-Kit-Seed' => 'k1OpaqueSeedToken_abc']
            ),
        ]);

        $config = '<svg xmlns="http://www.w3.org/2000/svg"><metadata id="kitroom-config">e30=</metadata></svg>';
        $result = (new KitKitchenClient)->fetch([
            'svg_body' => $config,
            'stars' => 1,
            'number' => '7',
        ]);

        $this->assertNotNull($result);
        $this->assertSame('k1OpaqueSeedToken_abc', $result['seed']);
        $this->assertStringContainsString('imported', $result['svg']);

        Http::assertSent(function ($request) use ($config) {
            return $request->method() === 'POST'
                && str_contains($request->url(), 'stars=1')
                && str_contains($request->url(), 'number=7')
                && $request->body() === $config
                && str_contains((string) $request->header('Content-Type')[0], 'image/svg+xml');
        });
    }
}
