<?php

namespace App\Console\Commands\Ffb;

use App\Services\WikimediaPlayerImageService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:wikimedia-debug {names*} {--json= : Optional path to write the full diagnosis JSON}')]
#[Description('HTTP-only debug for Wikidata SPARQL + Commons thumbnail lookups (no DB writes)')]
class WikimediaDebugCommand extends Command
{
    public function handle(WikimediaPlayerImageService $images): int
    {
        /** @var list<string> $names */
        $names = array_values(array_filter(
            array_map(static fn ($name): string => trim((string) $name), $this->argument('names')),
            static fn (string $name): bool => $name !== '',
        ));

        if ($names === []) {
            $this->error('Pass one or more player names, e.g. php artisan ffb:wikimedia-debug "Cristiano Ronaldo" "Lionel Messi"');

            return self::FAILURE;
        }

        $this->info('User-Agent: '.config('services.wikimedia.user_agent'));
        $ca = config('services.wikimedia.ca_bundle');
        $this->info('CA bundle: '.(is_string($ca) && is_file($ca) ? $ca : 'default (php.ini / system)'));
        $this->info('Diagnosing: '.implode(', ', $names));
        $this->newLine();

        $diagnosis = $images->diagnoseImagesByPlayerNames($names);

        $this->line('=== SPARQL ===');
        $this->line('URL: '.$diagnosis['sparql']['url']);
        $this->line('Status: '.($diagnosis['sparql']['status'] ?? 'n/a'));
        if ($diagnosis['sparql']['error']) {
            $this->error('Error: '.$diagnosis['sparql']['error']);
        }
        $this->line('Query:');
        $this->line($diagnosis['sparql']['query']);
        $this->line('Body:');
        $this->line($this->pretty($diagnosis['sparql']['body']));
        $this->newLine();

        foreach ($diagnosis['commons'] as $index => $commons) {
            $this->line('=== Commons #'.($index + 1).' ===');
            $this->line('URL: '.$commons['url']);
            $this->line('Titles: '.$commons['titles']);
            $this->line('Status: '.($commons['status'] ?? 'n/a'));
            if ($commons['error']) {
                $this->error('Error: '.$commons['error']);
            }
            $this->line('Body:');
            $this->line($this->pretty($commons['body']));
            $this->newLine();
        }

        $this->line('=== Parsed ===');
        $this->line('Bindings: '.$diagnosis['bindings_count']);
        $this->line('Files: '.$this->pretty($diagnosis['files']));
        $this->line('Thumbnails: '.$this->pretty($diagnosis['thumbnails']));
        $this->line('Resolved: '.$this->pretty($diagnosis['resolved']));

        $jsonPath = $this->option('json');
        if (is_string($jsonPath) && $jsonPath !== '') {
            File::ensureDirectoryExists(dirname($jsonPath));
            File::put($jsonPath, json_encode($diagnosis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            $this->info('Wrote '.$jsonPath);
        } else {
            $defaultPath = storage_path('logs/wikimedia-debug-last.json');
            File::put($defaultPath, json_encode($diagnosis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            $this->info('Wrote '.$defaultPath);
        }

        return self::SUCCESS;
    }

    private function pretty(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return (string) json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
