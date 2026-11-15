<?php

namespace Tests\Unit;

use App\Support\PlayerDisplayName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlayerDisplayNameTest extends TestCase
{
    #[Test]
    #[DataProvider('defaultCases')]
    public function default_follows_backfill_rules(string $fname, string $lname, string $expected): void
    {
        $this->assertSame($expected, PlayerDisplayName::default($fname, $lname));
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function defaultCases(): array
    {
        return [
            'fname equals lname' => ['Ronaldo', 'Ronaldo', 'Ronaldo'],
            'normal western name' => ['Luka', 'Modric', 'Luka Modric'],
            'multi-token kept as-is for default' => ['Rubin Rafael', 'Okotie', 'Rubin Rafael Okotie'],
            'trimmed' => ['  Luka  ', '  Modric ', 'Luka Modric'],
            'both empty' => ['', '', ''],
            'only fname' => ['Pelé', '', 'Pelé'],
            'only lname' => ['', 'Nedved', 'Nedved'],
        ];
    }

    #[Test]
    public function resolve_prefers_stored_display_name(): void
    {
        $this->assertSame(
            'Rubin Okotie',
            PlayerDisplayName::resolve('Rubin Okotie', 'Rubin Rafael', 'Okotie'),
        );
        $this->assertSame(
            'Rubin Rafael Okotie',
            PlayerDisplayName::resolve('', 'Rubin Rafael', 'Okotie'),
        );
    }
}
