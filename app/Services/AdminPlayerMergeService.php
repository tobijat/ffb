<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Userteam;
use App\Support\AssetKey;
use App\Support\PlayerPicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Throwable;

class AdminPlayerMergeService
{
    /**
     * @var list<string>
     */
    public const EDITABLE_FIELDS = [
        'player_fname',
        'player_lname',
        'player_nationality',
        'player_status',
        'player_status_description',
        'player_uefa_id',
        'player_fifa_id',
        'player_commons_image',
        'asset_key',
    ];

    /**
     * @var list<string>
     */
    public const PLAYERTEAM_EDITABLE_FIELDS = [
        'playerteam_player_position',
        'playerteam_status',
        'playerteam_player_note',
        'playerteam_player_picture',
        'playerteam_date_transfer',
    ];

    /**
     * @return array{
     *     ok: true,
     *     player_a: array<string, mixed>,
     *     player_b: array<string, mixed>,
     *     fields: list<array{key: string, label: string, value_a: mixed, value_b: mixed, default_side: string}>
     * }
     */
    public function prepare(int $playerAId, int $playerBId): array
    {
        if ($playerAId <= 0 || $playerBId <= 0 || $playerAId === $playerBId) {
            throw new InvalidArgumentException('Zwei unterschiedliche Spieler-IDs sind erforderlich.');
        }

        $playerA = $this->loadPlayerPayload($playerAId);
        $playerB = $this->loadPlayerPayload($playerBId);

        $fields = [];
        foreach (self::EDITABLE_FIELDS as $key) {
            if (! array_key_exists($key, $playerA['fields']) && ! Schema::hasColumn('ffb_player', $key)) {
                continue;
            }
            $valueA = $playerA['fields'][$key] ?? '';
            $valueB = $playerB['fields'][$key] ?? '';
            $field = [
                'key' => $key,
                'label' => $this->fieldLabel($key),
                'value_a' => $valueA,
                'value_b' => $valueB,
                'default_side' => $this->defaultFieldSide($valueA, $valueB),
            ];
            if ($key === 'asset_key') {
                $field['image_url_a'] = $playerA['image_url'] ?? '';
                $field['image_url_b'] = $playerB['image_url'] ?? '';
                $field['image_urls_a'] = $playerA['image_urls'] ?? [];
                $field['image_urls_b'] = $playerB['image_urls'] ?? [];
            }
            $fields[] = $field;
        }

        return [
            'ok' => true,
            'player_a' => $playerA,
            'player_b' => $playerB,
            'fields' => $fields,
            'collapses_if_a_kept' => $this->describeCollapses($playerAId, $playerBId),
            'collapses_if_b_kept' => $this->describeCollapses($playerBId, $playerAId),
        ];
    }

    /**
     * @param  array<string, mixed>  $fieldChoices  map field => 'a'|'b'|scalar (absolute value preferred via keep/drop mapping)
     * @param  list<array<string, mixed>>  $playerteamMerges
     * @return array{ok: true, keep_id: int, drop_id: int, summary: string, impact: array<string, mixed>, blockers: list<string>}
     */
    public function preview(int $keepId, int $dropId, array $fieldChoices = [], array $playerteamMerges = []): array
    {
        $plan = $this->buildPlan($keepId, $dropId, $fieldChoices, $playerteamMerges);

        return [
            'ok' => true,
            'keep_id' => $plan['keep_id'],
            'drop_id' => $plan['drop_id'],
            'summary' => $this->planSummary($plan),
            'impact' => $plan['impact'],
            'blockers' => $plan['blockers'],
            'can_execute' => $plan['blockers'] === [],
            'collapses' => $this->describeCollapses($keepId, $dropId),
        ];
    }

    /**
     * @param  array<string, mixed>  $fieldChoices
     * @param  list<array<string, mixed>>  $playerteamMerges
     * @return array{ok: true, keep_id: int, drop_id: int, message: string, impact: array<string, mixed>}
     */
    public function execute(int $keepId, int $dropId, array $fieldChoices, bool $confirm, array $playerteamMerges = []): array
    {
        if (! $confirm) {
            throw new InvalidArgumentException('Merge nicht bestätigt.');
        }

        $plan = $this->buildPlan($keepId, $dropId, $fieldChoices, $playerteamMerges);
        if ($plan['blockers'] !== []) {
            throw new InvalidArgumentException(implode(' ', $plan['blockers']));
        }

        $keepBefore = Player::query()->find($plan['keep_id']);
        $dropBefore = Player::query()->find($plan['drop_id']);
        $oldKeepKey = trim((string) ($keepBefore?->asset_key ?? ''));
        $oldDropKey = trim((string) ($dropBefore?->asset_key ?? ''));
        $chosenKey = trim((string) ($plan['resolved_fields']['asset_key'] ?? $oldKeepKey));

        try {
            DB::transaction(function () use ($plan, $chosenKey, $oldDropKey): void {
                foreach ($plan['collapses'] as $collapse) {
                    $this->executeCollapse($collapse);
                }
                foreach ($plan['repoints'] as $playerteamId) {
                    Playerteam::query()
                        ->where('playerteam_id', $playerteamId)
                        ->update(['playerteam_player_id' => $plan['keep_id']]);
                }

                $keep = Player::query()->find($plan['keep_id']);
                $drop = Player::query()->find($plan['drop_id']);
                if (! $keep || ! $drop) {
                    throw new InvalidArgumentException('Ziel-Spieler nicht gefunden.');
                }

                // Free unique asset_key on drop before assigning it to keep.
                if (
                    Schema::hasColumn('ffb_player', 'asset_key')
                    && $chosenKey !== ''
                    && $chosenKey === $oldDropKey
                    && $chosenKey !== trim((string) ($keep->asset_key ?? ''))
                ) {
                    $drop->asset_key = '';
                    $drop->save();
                }

                foreach ($plan['resolved_fields'] as $key => $value) {
                    if (! Schema::hasColumn('ffb_player', $key)) {
                        continue;
                    }
                    $keep->{$key} = $value;
                }
                $keep->save();

                $remaining = Playerteam::query()
                    ->where('playerteam_player_id', $plan['drop_id'])
                    ->count();
                if ($remaining > 0) {
                    throw new InvalidArgumentException('Merge abgebrochen: Verlierer hat noch Kader-Einträge.');
                }

                Player::query()->where('player_id', $plan['drop_id'])->delete();
            });
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);
            throw new InvalidArgumentException('Merge fehlgeschlagen: '.$e->getMessage());
        }

        $this->reconcilePlayerImagesAfterMerge(
            $plan['keep_id'],
            $plan['drop_id'],
            $oldKeepKey,
            $oldDropKey,
            $chosenKey,
        );

        return [
            'ok' => true,
            'keep_id' => $plan['keep_id'],
            'drop_id' => $plan['drop_id'],
            'message' => 'Spieler #'.$plan['drop_id'].' wurde in #'.$plan['keep_id'].' zusammengeführt.',
            'impact' => $plan['impact'],
        ];
    }

    /**
     * @param  array<string, mixed>  $fieldChoices
     * @param  list<array<string, mixed>>  $playerteamMerges
     * @return array{
     *     keep_id: int,
     *     drop_id: int,
     *     resolved_fields: array<string, mixed>,
     *     collapses: list<array{keep_pt: int, drop_pt: int, team_id: int, league_id: int, remaps: array<string, int>, discards: array<string, int>}>,
     *     repoints: list<int>,
     *     impact: array<string, mixed>,
     *     blockers: list<string>
     * }
     */
    private function buildPlan(int $keepId, int $dropId, array $fieldChoices, array $playerteamMerges = []): array
    {
        if ($keepId <= 0 || $dropId <= 0 || $keepId === $dropId) {
            throw new InvalidArgumentException('keep_id und drop_id müssen unterschiedlich und gültig sein.');
        }

        $keep = Player::query()->find($keepId);
        $drop = Player::query()->find($dropId);
        if (! $keep || ! $drop) {
            throw new InvalidArgumentException('Einer der Spieler wurde nicht gefunden.');
        }

        $resolvedFields = $this->resolveFields($keep, $drop, $fieldChoices);
        $mergeByPair = $this->indexPlayerteamMerges($playerteamMerges);

        $keepPts = Playerteam::query()
            ->where('playerteam_player_id', $keepId)
            ->orderBy('playerteam_id')
            ->get();
        $dropPts = Playerteam::query()
            ->where('playerteam_player_id', $dropId)
            ->orderBy('playerteam_id')
            ->get();

        /** @var array<string, Playerteam> $keepByKey */
        $keepByKey = [];
        foreach ($keepPts as $pt) {
            $keepByKey[$this->ptKey($pt)] = $pt;
        }

        $collapses = [];
        $repoints = [];
        $blockers = [];

        foreach ($dropPts as $dropPt) {
            $key = $this->ptKey($dropPt);
            if (isset($keepByKey[$key])) {
                $keepPt = $keepByKey[$key];
                $pairKey = (int) $keepPt->playerteam_id.'|'.(int) $dropPt->playerteam_id;
                $collapse = $this->planCollapse($keepPt, $dropPt, $mergeByPair[$pairKey] ?? []);
                $collapses[] = $collapse;
                foreach ($collapse['blockers'] as $blocker) {
                    $blockers[] = $blocker;
                }
            } else {
                $repoints[] = (int) $dropPt->playerteam_id;
            }
        }

        $impact = $this->buildImpact($keepId, $dropId, $collapses, $repoints, $resolvedFields);

        return [
            'keep_id' => $keepId,
            'drop_id' => $dropId,
            'resolved_fields' => $resolvedFields,
            'collapses' => array_map(static function (array $c): array {
                unset($c['blockers']);

                return $c;
            }, $collapses),
            'repoints' => $repoints,
            'impact' => $impact,
            'blockers' => array_values(array_unique($blockers)),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $playerteamMerges
     * @return array<string, array<string, mixed>>
     */
    private function indexPlayerteamMerges(array $playerteamMerges): array
    {
        $out = [];
        foreach ($playerteamMerges as $merge) {
            if (! is_array($merge)) {
                continue;
            }
            $keepPt = (int) ($merge['keep_pt'] ?? 0);
            $dropPt = (int) ($merge['drop_pt'] ?? 0);
            if ($keepPt <= 0 || $dropPt <= 0) {
                continue;
            }
            $out[$keepPt.'|'.$dropPt] = $merge;
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function describeCollapses(int $keepId, int $dropId): array
    {
        $keepPts = Playerteam::query()
            ->with(['team', 'league'])
            ->where('playerteam_player_id', $keepId)
            ->orderBy('playerteam_id')
            ->get();
        $dropPts = Playerteam::query()
            ->with(['team', 'league'])
            ->where('playerteam_player_id', $dropId)
            ->orderBy('playerteam_id')
            ->get();

        /** @var array<string, Playerteam> $keepByKey */
        $keepByKey = [];
        foreach ($keepPts as $pt) {
            $keepByKey[$this->ptKey($pt)] = $pt;
        }

        $out = [];
        foreach ($dropPts as $dropPt) {
            $key = $this->ptKey($dropPt);
            if (! isset($keepByKey[$key])) {
                continue;
            }
            $keepPt = $keepByKey[$key];
            $fields = [];
            foreach (self::PLAYERTEAM_EDITABLE_FIELDS as $fieldKey) {
                if (! Schema::hasColumn('ffb_playerteam', $fieldKey)) {
                    continue;
                }
                $valueKeep = $keepPt->{$fieldKey};
                $valueDrop = $dropPt->{$fieldKey};
                $fields[] = [
                    'key' => $fieldKey,
                    'label' => $this->playerteamFieldLabel($fieldKey),
                    'value_keep' => $valueKeep,
                    'value_drop' => $valueDrop,
                    'default_side' => $this->defaultFieldSide($valueKeep, $valueDrop) === 'b' ? 'drop' : 'keep',
                ];
            }
            $out[] = [
                'keep_pt' => (int) $keepPt->playerteam_id,
                'drop_pt' => (int) $dropPt->playerteam_id,
                'team_id' => (int) $dropPt->playerteam_team_id,
                'team_name' => (string) ($dropPt->team?->team_name ?? ''),
                'league_id' => (int) ($dropPt->playerteam_league_id ?? 0),
                'league_title' => (string) ($dropPt->league?->league_title ?? ''),
                'fields' => $fields,
                'child_conflicts' => [
                    'playerprice' => $this->describeChildConflicts('ffb_playerprice', 'playerprice_playerteam_id', 'playerprice_matchround_id', 'playerprice_price', (int) $keepPt->playerteam_id, (int) $dropPt->playerteam_id),
                    'playerstats' => $this->describeChildConflicts('ffb_playerstats', 'playerstats_playerteam_id', 'playerstats_matchround_id', 'playerstats_score', (int) $keepPt->playerteam_id, (int) $dropPt->playerteam_id),
                    'playerfid' => $this->describePlayerfidConflict((int) $keepPt->playerteam_id, (int) $dropPt->playerteam_id),
                ],
            ];
        }

        return $out;
    }

    /**
     * @return list<array{matchround_id: int, value_keep: mixed, value_drop: mixed, default_side: string}>
     */
    private function describeChildConflicts(
        string $table,
        string $ptColumn,
        string $roundColumn,
        string $valueColumn,
        int $keepPtId,
        int $dropPtId,
    ): array {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $ptColumn) || ! Schema::hasColumn($table, $roundColumn)) {
            return [];
        }
        $keepRows = DB::table($table)->where($ptColumn, $keepPtId)->get([$roundColumn, $valueColumn]);
        $dropRows = DB::table($table)->where($ptColumn, $dropPtId)->get([$roundColumn, $valueColumn]);
        $keepByRound = [];
        foreach ($keepRows as $row) {
            $keepByRound[(int) $row->{$roundColumn}] = $row->{$valueColumn};
        }
        $out = [];
        foreach ($dropRows as $row) {
            $roundId = (int) $row->{$roundColumn};
            if (! array_key_exists($roundId, $keepByRound)) {
                continue;
            }
            $out[] = [
                'matchround_id' => $roundId,
                'value_keep' => $keepByRound[$roundId],
                'value_drop' => $row->{$valueColumn},
                'default_side' => 'keep',
            ];
        }

        return $out;
    }

    /**
     * @return list<array{key: string, value_keep: mixed, value_drop: mixed, default_side: string}>
     */
    private function describePlayerfidConflict(int $keepPtId, int $dropPtId): array
    {
        if (! Schema::hasTable('ffb_playerfid')) {
            return [];
        }
        $keep = DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $keepPtId)->value('playerfid_name_wf');
        $drop = DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $dropPtId)->value('playerfid_name_wf');
        if ($keep === null || $drop === null) {
            return [];
        }

        return [[
            'key' => 'name',
            'value_keep' => $keep,
            'value_drop' => $drop,
            'default_side' => 'keep',
        ]];
    }

    /**
     * @param  array<string, mixed>  $mergeChoice
     * @return array{
     *     keep_pt: int,
     *     drop_pt: int,
     *     team_id: int,
     *     league_id: int,
     *     keep_position: string,
     *     drop_position: string,
     *     result_position: string,
     *     resolved_fields: array<string, mixed>,
     *     child_choices: array<string, mixed>,
     *     remaps: array<string, int>,
     *     discards: array<string, int>,
     *     blockers: list<string>
     * }
     */
    private function planCollapse(Playerteam $keepPt, Playerteam $dropPt, array $mergeChoice = []): array
    {
        $keepPtId = (int) $keepPt->playerteam_id;
        $dropPtId = (int) $dropPt->playerteam_id;
        $blockers = [];
        $remaps = [];
        $discards = [];

        $resolvedPtFields = $this->resolvePlayerteamFields($keepPt, $dropPt, is_array($mergeChoice['fields'] ?? null) ? $mergeChoice['fields'] : []);
        $childChoices = is_array($mergeChoice['children'] ?? null) ? $mergeChoice['children'] : [];

        $keepPos = strtolower(trim((string) ($keepPt->playerteam_player_position ?: '')));
        $dropPos = strtolower(trim((string) ($dropPt->playerteam_player_position ?: '')));
        $resultPos = strtolower(trim((string) ($resolvedPtFields['playerteam_player_position'] ?? $keepPos)));

        // Userteam starter slots: abort if same userteam already has keep PT in another slot.
        if (Schema::hasTable('ffb_userteam_slot')) {
            $dropSlots = DB::table('ffb_userteam_slot')
                ->where('userteam_slot_playerteam_id', $dropPtId)
                ->get(['userteam_slot_id', 'userteam_slot_userteam_id', 'userteam_slot_slot']);
            $remapCount = 0;
            foreach ($dropSlots as $slot) {
                $userteamId = (int) $slot->userteam_slot_userteam_id;
                $already = DB::table('ffb_userteam_slot')
                    ->where('userteam_slot_userteam_id', $userteamId)
                    ->where('userteam_slot_playerteam_id', $keepPtId)
                    ->exists();
                if ($already) {
                    $blockers[] = 'Userteam #'.$userteamId.' hätte nach dem Merge denselben Spieler zweimal in der Aufstellung (PT #'.$keepPtId.' / #'.$dropPtId.').';
                } elseif ($dropPos !== '' && $resultPos !== '' && $dropPos !== $resultPos) {
                    $blockers[] = 'Userteam #'.$userteamId.': gewählte Kader-Position '
                        .strtoupper($resultPos).' passt nicht zur bisherigen Position '
                        .strtoupper($dropPos).' (Slot '.(int) $slot->userteam_slot_slot.').';
                } else {
                    $remapCount++;
                }
            }
            if ($remapCount > 0) {
                $remaps['ffb_userteam_slot'] = $remapCount;
            }

            if ($keepPos !== '' && $resultPos !== '' && $keepPos !== $resultPos) {
                $keepSlots = DB::table('ffb_userteam_slot')
                    ->where('userteam_slot_playerteam_id', $keepPtId)
                    ->get(['userteam_slot_userteam_id', 'userteam_slot_slot']);
                foreach ($keepSlots as $slot) {
                    $blockers[] = 'Userteam #'.(int) $slot->userteam_slot_userteam_id
                        .': gewählte Kader-Position '.strtoupper($resultPos)
                        .' würde die bestehende Position '.strtoupper($keepPos)
                        .' von PT #'.$keepPtId.' ändern (Slot '.(int) $slot->userteam_slot_slot.').';
                }
            }
        }

        if (Schema::hasTable('ffb_userteam_substitute_slot')) {
            $dropSubs = DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_playerteam_id', $dropPtId)
                ->get(['substitute_slot_id', 'substitute_slot_userteam_id', 'substitute_slot_slot']);
            $remapCount = 0;
            foreach ($dropSubs as $slot) {
                $userteamId = (int) $slot->substitute_slot_userteam_id;
                $already = DB::table('ffb_userteam_substitute_slot')
                    ->where('substitute_slot_userteam_id', $userteamId)
                    ->where('substitute_slot_playerteam_id', $keepPtId)
                    ->exists();
                if ($already) {
                    $blockers[] = 'Userteam #'.$userteamId.' hätte nach dem Merge denselben Spieler zweimal auf der Bank (PT #'.$keepPtId.' / #'.$dropPtId.').';
                } elseif ($dropPos !== '' && $resultPos !== '' && $dropPos !== $resultPos) {
                    $blockers[] = 'Userteam #'.$userteamId.': gewählte Ersatz-Position '
                        .strtoupper($resultPos).' passt nicht zur bisherigen Position '
                        .strtoupper($dropPos).' (Bank-Slot '.(int) $slot->substitute_slot_slot.').';
                } else {
                    $remapCount++;
                }
            }
            if ($remapCount > 0) {
                $remaps['ffb_userteam_substitute_slot.playerteam'] = $remapCount;
            }

            $replacesCount = (int) DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_replaces_playerteam_id', $dropPtId)
                ->count();
            if ($replacesCount > 0) {
                $remaps['ffb_userteam_substitute_slot.replaces'] = $replacesCount;
            }
        }

        if (Schema::hasTable('ffb_extremeteam_slot')) {
            $count = (int) DB::table('ffb_extremeteam_slot')
                ->where('extremeteam_slot_playerteam_id', $dropPtId)
                ->count();
            if ($count > 0) {
                $remaps['ffb_extremeteam_slot'] = $count;
            }
        }

        // playerstats: conflicts resolved by childChoices (default: keep PT wins / drop discarded)
        if (Schema::hasTable('ffb_playerstats')) {
            $statsPlan = $this->planChildRoundConflicts(
                'ffb_playerstats',
                'playerstats_playerteam_id',
                'playerstats_matchround_id',
                $keepPtId,
                $dropPtId,
                is_array($childChoices['playerstats'] ?? null) ? $childChoices['playerstats'] : [],
            );
            if ($statsPlan['remap'] > 0) {
                $remaps['ffb_playerstats'] = $statsPlan['remap'];
            }
            if ($statsPlan['discard'] > 0) {
                $discards['ffb_playerstats'] = $statsPlan['discard'];
            }
        }

        if (Schema::hasTable('ffb_playerprice')) {
            $pricePlan = $this->planChildRoundConflicts(
                'ffb_playerprice',
                'playerprice_playerteam_id',
                'playerprice_matchround_id',
                $keepPtId,
                $dropPtId,
                is_array($childChoices['playerprice'] ?? null) ? $childChoices['playerprice'] : [],
            );
            if ($pricePlan['remap'] > 0) {
                $remaps['ffb_playerprice'] = $pricePlan['remap'];
            }
            if ($pricePlan['discard'] > 0) {
                $discards['ffb_playerprice'] = $pricePlan['discard'];
            }
        }

        if (Schema::hasTable('ffb_goal')) {
            $count = (int) DB::table('ffb_goal')->where('goal_playerteam_id', $dropPtId)->count();
            if ($count > 0) {
                $remaps['ffb_goal'] = $count;
            }
        }

        if (Schema::hasTable('ffb_psgoal')) {
            $count = (int) DB::table('ffb_psgoal')->where('psgoal_playerteam_id', $dropPtId)->count();
            if ($count > 0) {
                $remaps['ffb_psgoal'] = $count;
            }
        }

        if (Schema::hasTable('ffb_playerfid')) {
            $keepHas = DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $keepPtId)->exists();
            $dropCount = (int) DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $dropPtId)->count();
            if ($dropCount > 0) {
                $fidChoice = (string) (($childChoices['playerfid']['name'] ?? null) ?: 'keep');
                if ($keepHas && $fidChoice === 'drop') {
                    $discards['ffb_playerfid'] = 1; // keep row discarded, drop remapped
                    $remaps['ffb_playerfid'] = $dropCount;
                } elseif ($keepHas) {
                    $discards['ffb_playerfid'] = $dropCount;
                } else {
                    $remaps['ffb_playerfid'] = $dropCount;
                }
            }
        }

        if (Userteam::hasWideSlotColumns()) {
            for ($i = 1; $i <= 11; $i++) {
                $col = 'userteam_player_id'.$i;
                $count = (int) DB::table('ffb_userteam')->where($col, $dropPtId)->count();
                if ($count > 0) {
                    $remaps['ffb_userteam.'.$col] = $count;
                }
            }
        }

        return [
            'keep_pt' => $keepPtId,
            'drop_pt' => $dropPtId,
            'team_id' => (int) $dropPt->playerteam_team_id,
            'league_id' => (int) ($dropPt->playerteam_league_id ?? 0),
            'keep_position' => $keepPos,
            'drop_position' => $dropPos,
            'result_position' => $resultPos,
            'resolved_fields' => $resolvedPtFields,
            'child_choices' => $childChoices,
            'remaps' => $remaps,
            'discards' => $discards,
            'blockers' => $blockers,
        ];
    }

    /**
     * @param  array<string, string>  $choices  matchround_id => keep|drop
     * @return array{remap: int, discard: int}
     */
    private function planChildRoundConflicts(
        string $table,
        string $ptColumn,
        string $roundColumn,
        int $keepPtId,
        int $dropPtId,
        array $choices,
    ): array {
        $keepRounds = DB::table($table)
            ->where($ptColumn, $keepPtId)
            ->pluck($roundColumn)
            ->map(static fn ($id): int => (int) $id)
            ->all();
        $keepRoundSet = array_fill_keys($keepRounds, true);
        $dropRows = DB::table($table)
            ->where($ptColumn, $dropPtId)
            ->get([$roundColumn]);
        $remap = 0;
        $discard = 0;
        foreach ($dropRows as $row) {
            $roundId = (int) $row->{$roundColumn};
            if (! isset($keepRoundSet[$roundId])) {
                $remap++;
                continue;
            }
            $choice = strtolower((string) ($choices[(string) $roundId] ?? $choices[$roundId] ?? 'keep'));
            if ($choice === 'drop') {
                // keep row discarded, drop remapped
                $discard++;
                $remap++;
            } else {
                $discard++;
            }
        }

        return ['remap' => $remap, 'discard' => $discard];
    }

    /**
     * @param  array<string, mixed>  $fieldChoices
     * @return array<string, mixed>
     */
    private function resolvePlayerteamFields(Playerteam $keepPt, Playerteam $dropPt, array $fieldChoices): array
    {
        $out = [];
        foreach (self::PLAYERTEAM_EDITABLE_FIELDS as $key) {
            if (! Schema::hasColumn('ffb_playerteam', $key)) {
                continue;
            }
            $keepVal = $keepPt->{$key};
            $dropVal = $dropPt->{$key};
            $choice = $fieldChoices[$key] ?? null;
            if ($choice === 'drop') {
                $out[$key] = $dropVal;
            } elseif ($choice === 'keep') {
                $out[$key] = $keepVal;
            } elseif ($choice !== null && $choice !== '') {
                $out[$key] = $key === 'playerteam_status' ? (int) $choice : $choice;
            } else {
                $keepEmpty = $this->isEmptyFieldValue($keepVal);
                $dropEmpty = $this->isEmptyFieldValue($dropVal);
                $out[$key] = (! $keepEmpty || $dropEmpty) ? $keepVal : $dropVal;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $collapse
     */
    private function executeCollapse(array $collapse): void
    {
        $keepPtId = (int) $collapse['keep_pt'];
        $dropPtId = (int) $collapse['drop_pt'];
        $keepPos = strtolower(trim((string) ($collapse['keep_position'] ?? '')));
        $dropPos = strtolower(trim((string) ($collapse['drop_position'] ?? '')));
        $resultPos = strtolower(trim((string) ($collapse['result_position'] ?? $keepPos)));
        $resolvedFields = is_array($collapse['resolved_fields'] ?? null) ? $collapse['resolved_fields'] : [];
        $childChoices = is_array($collapse['child_choices'] ?? null) ? $collapse['child_choices'] : [];

        $keepPt = Playerteam::query()->find($keepPtId);
        if (! $keepPt) {
            throw new InvalidArgumentException('Ziel-Kader PT #'.$keepPtId.' nicht gefunden.');
        }
        foreach ($resolvedFields as $key => $value) {
            if (! Schema::hasColumn('ffb_playerteam', $key)) {
                continue;
            }
            $keepPt->{$key} = $value;
        }
        $keepPt->save();

        if (Schema::hasTable('ffb_userteam_slot')) {
            // Re-check guard inside transaction
            $dropSlots = DB::table('ffb_userteam_slot')
                ->where('userteam_slot_playerteam_id', $dropPtId)
                ->get(['userteam_slot_id', 'userteam_slot_userteam_id', 'userteam_slot_slot']);
            foreach ($dropSlots as $slot) {
                $already = DB::table('ffb_userteam_slot')
                    ->where('userteam_slot_userteam_id', (int) $slot->userteam_slot_userteam_id)
                    ->where('userteam_slot_playerteam_id', $keepPtId)
                    ->exists();
                if ($already) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->userteam_slot_userteam_id.' hätte denselben Spieler zweimal.'
                    );
                }
                if ($dropPos !== '' && $resultPos !== '' && $dropPos !== $resultPos) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->userteam_slot_userteam_id
                        .': Kader-Position würde sich ändern ('
                        .strtoupper($dropPos).' → '.strtoupper($resultPos).').'
                    );
                }
            }
            if ($keepPos !== '' && $resultPos !== '' && $keepPos !== $resultPos) {
                $keepSlots = DB::table('ffb_userteam_slot')
                    ->where('userteam_slot_playerteam_id', $keepPtId)
                    ->get(['userteam_slot_userteam_id', 'userteam_slot_slot']);
                foreach ($keepSlots as $slot) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->userteam_slot_userteam_id
                        .': Kader-Position würde sich ändern ('
                        .strtoupper($keepPos).' → '.strtoupper($resultPos).').'
                    );
                }
            }

            DB::table('ffb_userteam_slot')
                ->where('userteam_slot_playerteam_id', $dropPtId)
                ->update(['userteam_slot_playerteam_id' => $keepPtId]);
        }

        if (Schema::hasTable('ffb_userteam_substitute_slot')) {
            $dropSubs = DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_playerteam_id', $dropPtId)
                ->get(['substitute_slot_id', 'substitute_slot_userteam_id', 'substitute_slot_slot']);
            foreach ($dropSubs as $slot) {
                $already = DB::table('ffb_userteam_substitute_slot')
                    ->where('substitute_slot_userteam_id', (int) $slot->substitute_slot_userteam_id)
                    ->where('substitute_slot_playerteam_id', $keepPtId)
                    ->exists();
                if ($already) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->substitute_slot_userteam_id.' hätte denselben Ersatzspieler zweimal.'
                    );
                }
                if ($dropPos !== '' && $resultPos !== '' && $dropPos !== $resultPos) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->substitute_slot_userteam_id
                        .': Ersatz-Position würde sich ändern ('
                        .strtoupper($dropPos).' → '.strtoupper($resultPos).').'
                    );
                }
            }
            if ($keepPos !== '' && $resultPos !== '' && $keepPos !== $resultPos) {
                $keepSubs = DB::table('ffb_userteam_substitute_slot')
                    ->where('substitute_slot_playerteam_id', $keepPtId)
                    ->get(['substitute_slot_userteam_id', 'substitute_slot_slot']);
                foreach ($keepSubs as $slot) {
                    throw new InvalidArgumentException(
                        'Userteam #'.(int) $slot->substitute_slot_userteam_id
                        .': Ersatz-Position würde sich ändern ('
                        .strtoupper($keepPos).' → '.strtoupper($resultPos).').'
                    );
                }
            }
            DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_playerteam_id', $dropPtId)
                ->update(['substitute_slot_playerteam_id' => $keepPtId]);
            DB::table('ffb_userteam_substitute_slot')
                ->where('substitute_slot_replaces_playerteam_id', $dropPtId)
                ->update(['substitute_slot_replaces_playerteam_id' => $keepPtId]);
        }

        if (Schema::hasTable('ffb_extremeteam_slot')) {
            DB::table('ffb_extremeteam_slot')
                ->where('extremeteam_slot_playerteam_id', $dropPtId)
                ->update(['extremeteam_slot_playerteam_id' => $keepPtId]);
        }

        if (Schema::hasTable('ffb_playerstats')) {
            $this->executeChildRoundConflicts(
                'ffb_playerstats',
                'playerstats_playerteam_id',
                'playerstats_matchround_id',
                $keepPtId,
                $dropPtId,
                is_array($childChoices['playerstats'] ?? null) ? $childChoices['playerstats'] : [],
            );
        }

        if (Schema::hasTable('ffb_playerprice')) {
            $this->executeChildRoundConflicts(
                'ffb_playerprice',
                'playerprice_playerteam_id',
                'playerprice_matchround_id',
                $keepPtId,
                $dropPtId,
                is_array($childChoices['playerprice'] ?? null) ? $childChoices['playerprice'] : [],
            );
        }

        if (Schema::hasTable('ffb_goal')) {
            DB::table('ffb_goal')
                ->where('goal_playerteam_id', $dropPtId)
                ->update(['goal_playerteam_id' => $keepPtId]);
        }

        if (Schema::hasTable('ffb_psgoal')) {
            DB::table('ffb_psgoal')
                ->where('psgoal_playerteam_id', $dropPtId)
                ->update(['psgoal_playerteam_id' => $keepPtId]);
        }

        if (Schema::hasTable('ffb_playerfid')) {
            $keepHas = DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $keepPtId)->exists();
            $fidChoice = strtolower((string) (($childChoices['playerfid']['name'] ?? null) ?: 'keep'));
            if ($keepHas && $fidChoice === 'drop') {
                DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $keepPtId)->delete();
                DB::table('ffb_playerfid')
                    ->where('playerfid_playerteam_id', $dropPtId)
                    ->update(['playerfid_playerteam_id' => $keepPtId]);
            } elseif ($keepHas) {
                DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $dropPtId)->delete();
            } else {
                DB::table('ffb_playerfid')
                    ->where('playerfid_playerteam_id', $dropPtId)
                    ->update(['playerfid_playerteam_id' => $keepPtId]);
            }
        }

        if (Userteam::hasWideSlotColumns()) {
            for ($i = 1; $i <= 11; $i++) {
                $col = 'userteam_player_id'.$i;
                DB::table('ffb_userteam')->where($col, $dropPtId)->update([$col => $keepPtId]);
            }
        }

        // Any leftover child rows still pointing at drop PT get deleted with the PT.
        if (Schema::hasTable('ffb_playerprice')) {
            DB::table('ffb_playerprice')->where('playerprice_playerteam_id', $dropPtId)->delete();
        }
        if (Schema::hasTable('ffb_playerfid')) {
            DB::table('ffb_playerfid')->where('playerfid_playerteam_id', $dropPtId)->delete();
        }

        Playerteam::query()->where('playerteam_id', $dropPtId)->delete();
    }

    /**
     * @param  array<string, string>  $choices
     */
    private function executeChildRoundConflicts(
        string $table,
        string $ptColumn,
        string $roundColumn,
        int $keepPtId,
        int $dropPtId,
        array $choices,
    ): void {
        $keepRounds = DB::table($table)
            ->where($ptColumn, $keepPtId)
            ->pluck($roundColumn)
            ->map(static fn ($id): int => (int) $id)
            ->all();
        $keepRoundSet = array_fill_keys($keepRounds, true);

        $dropConflictRounds = [];
        $preferDropRounds = [];
        foreach (DB::table($table)->where($ptColumn, $dropPtId)->pluck($roundColumn) as $round) {
            $roundId = (int) $round;
            if (! isset($keepRoundSet[$roundId])) {
                continue;
            }
            $dropConflictRounds[] = $roundId;
            $choice = strtolower((string) ($choices[(string) $roundId] ?? $choices[$roundId] ?? 'keep'));
            if ($choice === 'drop') {
                $preferDropRounds[] = $roundId;
            }
        }

        if ($preferDropRounds !== []) {
            DB::table($table)
                ->where($ptColumn, $keepPtId)
                ->whereIn($roundColumn, $preferDropRounds)
                ->delete();
        }
        $discardDropRounds = array_values(array_diff($dropConflictRounds, $preferDropRounds));
        if ($discardDropRounds !== []) {
            DB::table($table)
                ->where($ptColumn, $dropPtId)
                ->whereIn($roundColumn, $discardDropRounds)
                ->delete();
        }

        DB::table($table)
            ->where($ptColumn, $dropPtId)
            ->update([$ptColumn => $keepPtId]);
    }

    /**
     * @param  list<array{keep_pt: int, drop_pt: int, team_id: int, league_id: int, remaps: array<string, int>, discards: array<string, int>}>  $collapses
     * @param  list<int>  $repoints
     * @param  array<string, mixed>  $resolvedFields
     * @return array<string, mixed>
     */
    private function buildImpact(
        int $keepId,
        int $dropId,
        array $collapses,
        array $repoints,
        array $resolvedFields,
    ): array {
        $tableTotals = [];
        $discardTotals = [];
        foreach ($collapses as $collapse) {
            foreach ($collapse['remaps'] as $table => $count) {
                $tableTotals[$table] = ($tableTotals[$table] ?? 0) + $count;
            }
            foreach ($collapse['discards'] as $table => $count) {
                $discardTotals[$table] = ($discardTotals[$table] ?? 0) + $count;
            }
        }

        $collapseByDropPt = [];
        foreach ($collapses as $collapse) {
            $collapseByDropPt[(int) $collapse['drop_pt']] = $collapse;
        }

        $dropPts = Playerteam::query()
            ->with(['team', 'league'])
            ->where('playerteam_player_id', $dropId)
            ->orderBy('playerteam_id')
            ->get();

        $kaderChanges = [];
        foreach ($dropPts as $pt) {
            $ptId = (int) $pt->playerteam_id;
            $teamName = (string) ($pt->team?->team_name ?? '');
            $leagueTitle = (string) ($pt->league?->league_title ?? '');
            if (isset($collapseByDropPt[$ptId])) {
                $collapse = $collapseByDropPt[$ptId];
                $kaderChanges[] = [
                    'action' => 'collapse',
                    'label' => 'Zusammenlegen',
                    'drop_playerteam_id' => $ptId,
                    'keep_playerteam_id' => (int) $collapse['keep_pt'],
                    'team_id' => (int) $pt->playerteam_team_id,
                    'team_name' => $teamName !== '' ? $teamName : ('Team #'.(int) $pt->playerteam_team_id),
                    'league_id' => (int) ($pt->playerteam_league_id ?? 0),
                    'league_title' => $leagueTitle !== '' ? $leagueTitle : ('Liga #'.(int) ($pt->playerteam_league_id ?? 0)),
                    'position' => (string) ($pt->playerteam_player_position ?: ''),
                    'detail' => 'PT #'.$ptId.' wird in PT #'.(int) $collapse['keep_pt'].' überführt und gelöscht',
                ];
            } else {
                $kaderChanges[] = [
                    'action' => 'repoint',
                    'label' => 'Umhängen',
                    'drop_playerteam_id' => $ptId,
                    'keep_playerteam_id' => $ptId,
                    'team_id' => (int) $pt->playerteam_team_id,
                    'team_name' => $teamName !== '' ? $teamName : ('Team #'.(int) $pt->playerteam_team_id),
                    'league_id' => (int) ($pt->playerteam_league_id ?? 0),
                    'league_title' => $leagueTitle !== '' ? $leagueTitle : ('Liga #'.(int) ($pt->playerteam_league_id ?? 0)),
                    'position' => (string) ($pt->playerteam_player_position ?: ''),
                    'detail' => 'PT #'.$ptId.' bleibt, player_id '.$dropId.' → '.$keepId,
                ];
            }
        }

        $dropPtIds = $dropPts->map(static fn (Playerteam $pt): int => (int) $pt->playerteam_id)->all();
        $affectedUsers = $this->affectedUsersForPlayerteams($dropPtIds);
        $affectedExtremes = $this->affectedExtremeteamsForPlayerteams($dropPtIds);

        $keep = Player::query()->find($keepId);
        $drop = Player::query()->find($dropId);
        $oldKeepKey = trim((string) ($keep?->asset_key ?? ''));
        $oldDropKey = trim((string) ($drop?->asset_key ?? ''));
        $chosenKey = trim((string) ($resolvedFields['asset_key'] ?? $oldKeepKey));
        $abandonedKey = $chosenKey === $oldDropKey ? $oldKeepKey : $oldDropKey;
        if ($abandonedKey === $chosenKey) {
            $abandonedKey = '';
        }

        $collapseRows = array_map(static function (array $c): array {
            return [
                'keep_playerteam_id' => $c['keep_pt'],
                'drop_playerteam_id' => $c['drop_pt'],
                'team_id' => $c['team_id'],
                'league_id' => $c['league_id'],
                'keep_position' => $c['keep_position'] ?? '',
                'drop_position' => $c['drop_position'] ?? '',
                'remaps' => $c['remaps'],
                'discards' => $c['discards'],
            ];
        }, $collapses);

        return [
            'keep_player_id' => $keepId,
            'drop_player_id' => $dropId,
            'playerteams_repointed' => count($repoints),
            'playerteams_collapsed' => count($collapses),
            'playerteams_repoint_ids' => $repoints,
            'kader_changes' => $kaderChanges,
            'affected_users' => $affectedUsers,
            'affected_users_count' => count($affectedUsers),
            'affected_extremeteams' => $affectedExtremes,
            'collapses' => $collapseRows,
            'rows_updated_by_table' => $tableTotals,
            'rows_discarded_by_table' => $discardTotals,
            'fields' => $resolvedFields,
            'image' => [
                'chosen_asset_key' => $chosenKey,
                'abandoned_asset_key' => $abandonedKey,
                'note' => $chosenKey === $oldDropKey && $chosenKey !== ''
                    ? 'Asset-Key/Bild von Spieler #'.$dropId.' wird übernommen; Dateien unter dem anderen Key werden bereinigt.'
                    : 'Asset-Key/Bild von Spieler #'.$keepId.' bleibt; fehlende Bilder vom anderen Key werden nach Möglichkeit kopiert.',
            ],
            'drop_player_deleted' => true,
        ];
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return list<array{
     *     user_id: int,
     *     user_nickname: string,
     *     userteam_id: int,
     *     matchround_id: int,
     *     role: string,
     *     playerteam_id: int,
     *     slot: int
     * }>
     */
    private function affectedUsersForPlayerteams(array $playerteamIds): array
    {
        $playerteamIds = array_values(array_unique(array_filter(
            array_map(static fn ($id): int => (int) $id, $playerteamIds),
            static fn (int $id): bool => $id > 0,
        )));
        if ($playerteamIds === []) {
            return [];
        }

        $rows = [];
        $userteamIds = [];

        if (Schema::hasTable('ffb_userteam_slot')) {
            $slots = DB::table('ffb_userteam_slot')
                ->whereIn('userteam_slot_playerteam_id', $playerteamIds)
                ->orderBy('userteam_slot_userteam_id')
                ->orderBy('userteam_slot_slot')
                ->get([
                    'userteam_slot_userteam_id',
                    'userteam_slot_slot',
                    'userteam_slot_playerteam_id',
                ]);
            foreach ($slots as $slot) {
                $userteamId = (int) $slot->userteam_slot_userteam_id;
                $userteamIds[$userteamId] = true;
                $rows[] = [
                    'userteam_id' => $userteamId,
                    'matchround_id' => 0,
                    'user_id' => 0,
                    'user_nickname' => '',
                    'role' => 'Aufstellung',
                    'playerteam_id' => (int) $slot->userteam_slot_playerteam_id,
                    'slot' => (int) $slot->userteam_slot_slot,
                ];
            }
        }

        if (Schema::hasTable('ffb_userteam_substitute_slot')) {
            $subs = DB::table('ffb_userteam_substitute_slot')
                ->where(function ($query) use ($playerteamIds): void {
                    $query->whereIn('substitute_slot_playerteam_id', $playerteamIds)
                        ->orWhereIn('substitute_slot_replaces_playerteam_id', $playerteamIds);
                })
                ->orderBy('substitute_slot_userteam_id')
                ->orderBy('substitute_slot_slot')
                ->get([
                    'substitute_slot_userteam_id',
                    'substitute_slot_slot',
                    'substitute_slot_playerteam_id',
                    'substitute_slot_replaces_playerteam_id',
                ]);
            foreach ($subs as $slot) {
                $userteamId = (int) $slot->substitute_slot_userteam_id;
                $userteamIds[$userteamId] = true;
                $asPlayer = in_array((int) $slot->substitute_slot_playerteam_id, $playerteamIds, true);
                $rows[] = [
                    'userteam_id' => $userteamId,
                    'matchround_id' => 0,
                    'user_id' => 0,
                    'user_nickname' => '',
                    'role' => $asPlayer ? 'Ersatzbank' : 'Ersatz-Bezug',
                    'playerteam_id' => $asPlayer
                        ? (int) $slot->substitute_slot_playerteam_id
                        : (int) $slot->substitute_slot_replaces_playerteam_id,
                    'slot' => (int) $slot->substitute_slot_slot,
                ];
            }
        }

        if ($rows === [] || $userteamIds === []) {
            return [];
        }

        $userteams = DB::table('ffb_userteam')
            ->whereIn('userteam_id', array_keys($userteamIds))
            ->get(['userteam_id', 'userteam_user_id', 'userteam_matchround_id'])
            ->keyBy(static fn ($row): int => (int) $row->userteam_id);

        $userIds = [];
        foreach ($userteams as $ut) {
            $uid = (int) $ut->userteam_user_id;
            if ($uid > 0) {
                $userIds[$uid] = true;
            }
        }

        $nicknames = [];
        if ($userIds !== [] && Schema::hasTable('web_user') && Schema::hasColumn('web_user', 'user_nickname')) {
            $nicknames = DB::table('web_user')
                ->whereIn('user_id', array_keys($userIds))
                ->pluck('user_nickname', 'user_id')
                ->map(static fn ($n): string => (string) $n)
                ->all();
        }

        foreach ($rows as $index => $row) {
            $ut = $userteams[$row['userteam_id']] ?? null;
            $userId = $ut ? (int) $ut->userteam_user_id : 0;
            $rows[$index]['user_id'] = $userId;
            $rows[$index]['matchround_id'] = $ut ? (int) ($ut->userteam_matchround_id ?? 0) : 0;
            $rows[$index]['user_nickname'] = $nicknames[$userId] ?? '';
        }

        usort($rows, static function (array $a, array $b): int {
            $nick = strcasecmp($a['user_nickname'], $b['user_nickname']);
            if ($nick !== 0) {
                return $nick;
            }
            $uid = $a['user_id'] <=> $b['user_id'];
            if ($uid !== 0) {
                return $uid;
            }

            return $a['userteam_id'] <=> $b['userteam_id'];
        });

        return $rows;
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return list<array{extremeteam_id: int, top_or_flop: string, matchround_id: int, playerteam_id: int, slot: int}>
     */
    private function affectedExtremeteamsForPlayerteams(array $playerteamIds): array
    {
        $playerteamIds = array_values(array_unique(array_filter(
            array_map(static fn ($id): int => (int) $id, $playerteamIds),
            static fn (int $id): bool => $id > 0,
        )));
        if ($playerteamIds === [] || ! Schema::hasTable('ffb_extremeteam_slot')) {
            return [];
        }

        $query = DB::table('ffb_extremeteam_slot as s')
            ->whereIn('s.extremeteam_slot_playerteam_id', $playerteamIds)
            ->orderBy('s.extremeteam_slot_extremeteam_id')
            ->orderBy('s.extremeteam_slot_slot');

        if (Schema::hasTable('ffb_extremeteam')) {
            $query->leftJoin('ffb_extremeteam as e', 'e.extremeteam_id', '=', 's.extremeteam_slot_extremeteam_id')
                ->select([
                    's.extremeteam_slot_extremeteam_id',
                    's.extremeteam_slot_slot',
                    's.extremeteam_slot_playerteam_id',
                    'e.extremeteam_top_or_flop',
                    'e.extremeteam_matchround_id',
                ]);
        } else {
            $query->select([
                's.extremeteam_slot_extremeteam_id',
                's.extremeteam_slot_slot',
                's.extremeteam_slot_playerteam_id',
            ]);
        }

        $out = [];
        foreach ($query->get() as $row) {
            $out[] = [
                'extremeteam_id' => (int) $row->extremeteam_slot_extremeteam_id,
                'top_or_flop' => (string) ($row->extremeteam_top_or_flop ?? ''),
                'matchround_id' => (int) ($row->extremeteam_matchround_id ?? 0),
                'playerteam_id' => (int) $row->extremeteam_slot_playerteam_id,
                'slot' => (int) $row->extremeteam_slot_slot,
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    private function planSummary(array $plan): string
    {
        if ($plan['blockers'] !== []) {
            return 'Merge blockiert: '.count($plan['blockers']).' Konflikt(e).';
        }
        $parts = [
            'Behalten #'.$plan['keep_id'],
            'löschen #'.$plan['drop_id'],
        ];
        $impact = $plan['impact'];
        if ((int) ($impact['playerteams_repointed'] ?? 0) > 0) {
            $parts[] = (int) $impact['playerteams_repointed'].' Kader umhängen';
        }
        if ((int) ($impact['playerteams_collapsed'] ?? 0) > 0) {
            $parts[] = (int) $impact['playerteams_collapsed'].' Kader zusammenlegen';
        }
        $updated = array_sum($impact['rows_updated_by_table'] ?? []);
        if ($updated > 0) {
            $parts[] = $updated.' abhängige Zeilen aktualisieren';
        }
        $discarded = array_sum($impact['rows_discarded_by_table'] ?? []);
        if ($discarded > 0) {
            $parts[] = $discarded.' doppelte Zeilen verwerfen';
        }

        return implode(' · ', $parts);
    }

    /**
     * Accepts either absolute field values, or side markers:
     * - 'keep' / 'drop' relative to keep/drop players
     * - 'a' / 'b' only when fieldChoices also contains _player_a_id / _player_b_id
     *
     * @param  array<string, mixed>  $fieldChoices
     * @return array<string, mixed>
     */
    private function resolveFields(Player $keep, Player $drop, array $fieldChoices): array
    {
        $playerAId = (int) ($fieldChoices['_player_a_id'] ?? 0);
        $playerBId = (int) ($fieldChoices['_player_b_id'] ?? 0);

        $out = [];
        foreach (self::EDITABLE_FIELDS as $key) {
            if (! Schema::hasColumn('ffb_player', $key)) {
                continue;
            }
            $keepVal = $keep->{$key};
            $dropVal = $drop->{$key};
            $choice = $fieldChoices[$key] ?? null;

            if ($choice === 'keep') {
                $out[$key] = $keepVal;
                continue;
            }
            if ($choice === 'drop') {
                $out[$key] = $dropVal;
                continue;
            }
            if ($choice === 'a' && $playerAId > 0) {
                $out[$key] = $playerAId === (int) $keep->player_id ? $keepVal : $dropVal;
                continue;
            }
            if ($choice === 'b' && $playerBId > 0) {
                $out[$key] = $playerBId === (int) $keep->player_id ? $keepVal : $dropVal;
                continue;
            }
            if ($choice !== null && $choice !== '') {
                $out[$key] = $key === 'player_status'
                    ? (int) $choice
                    : (is_string($choice) ? trim($choice) : $choice);
                continue;
            }

            $keepEmpty = $this->isEmptyFieldValue($keepVal);
            $dropEmpty = $this->isEmptyFieldValue($dropVal);
            $out[$key] = (! $keepEmpty || $dropEmpty) ? $keepVal : $dropVal;
        }

        return $out;
    }

    /**
     * @return array{
     *     player_id: int,
     *     asset_key: string,
     *     fields: array<string, mixed>,
     *     squads: list<array<string, mixed>>
     * }
     */
    private function loadPlayerPayload(int $playerId): array
    {
        $player = Player::query()->find($playerId);
        if (! $player) {
            throw new InvalidArgumentException('Spieler #'.$playerId.' nicht gefunden.');
        }

        $fields = [];
        foreach (self::EDITABLE_FIELDS as $key) {
            if (! Schema::hasColumn('ffb_player', $key)) {
                continue;
            }
            $fields[$key] = $player->{$key};
        }

        $squads = [];
        $rows = Playerteam::query()
            ->with(['team', 'league'])
            ->where('playerteam_player_id', $playerId)
            ->orderBy('playerteam_id')
            ->get();
        $teamKeys = [];
        foreach ($rows as $row) {
            $teamKey = (string) ($row->team?->asset_key ?? '');
            if (AssetKey::isValid($teamKey)) {
                $teamKeys[$teamKey] = true;
            }
            $squads[] = [
                'playerteam_id' => (int) $row->playerteam_id,
                'team_id' => (int) $row->playerteam_team_id,
                'team_name' => (string) ($row->team?->team_name ?? ''),
                'league_id' => (int) ($row->playerteam_league_id ?? 0),
                'league_title' => (string) ($row->league?->league_title ?? ''),
                'position' => (string) ($row->playerteam_player_position ?: ''),
                'status' => (int) ($row->playerteam_status ? 1 : 0),
            ];
        }

        $imageUrls = $this->imageUrlsForPlayerKey(
            (string) ($player->asset_key ?? ''),
            array_keys($teamKeys),
        );

        return [
            'player_id' => $playerId,
            'asset_key' => (string) ($player->asset_key ?? ''),
            'fields' => $fields,
            'squads' => $squads,
            'image_url' => $imageUrls[0] ?? '',
            'image_urls' => $imageUrls,
            'display_name' => trim((string) ($player->player_lname ?? '').', '.(string) ($player->player_fname ?? ''), ' ,'),
        ];
    }

    /**
     * @param  list<string>  $teamKeys
     * @return list<string>
     */
    private function imageUrlsForPlayerKey(string $playerKey, array $teamKeys): array
    {
        $urls = [];
        if (! AssetKey::isValid($playerKey)) {
            return $urls;
        }
        foreach ($teamKeys as $teamKey) {
            $url = PlayerPicture::url($teamKey, $playerKey);
            if (! str_ends_with($url, 'image_na.gif')) {
                $urls[$url] = true;
            }
        }
        $flat = PlayerPicture::url(null, $playerKey);
        if (! str_ends_with($flat, 'image_na.gif')) {
            $urls[$flat] = true;
        }

        return array_keys($urls);
    }

    private function ptKey(Playerteam $pt): string
    {
        return (int) $pt->playerteam_team_id.'|'.(int) ($pt->playerteam_league_id ?? 0);
    }

    private function fieldLabel(string $key): string
    {
        return match ($key) {
            'player_fname' => 'Vorname',
            'player_lname' => 'Nachname',
            'player_nationality' => 'Nationalität',
            'player_status' => 'Status',
            'player_status_description' => 'Status-Text',
            'player_uefa_id' => 'UEFA-ID',
            'player_fifa_id' => 'FIFA-ID',
            'player_commons_image' => 'Commons-Bild',
            'asset_key' => 'Bild / Asset-Key',
            default => $key,
        };
    }

    private function playerteamFieldLabel(string $key): string
    {
        return match ($key) {
            'playerteam_player_position' => 'Position',
            'playerteam_status' => 'Kader-Status',
            'playerteam_player_note' => 'Notiz',
            'playerteam_player_picture' => 'Kader-Bild',
            'playerteam_date_transfer' => 'Transferdatum',
            default => $key,
        };
    }

    private function defaultFieldSide(mixed $valueA, mixed $valueB): string
    {
        $emptyA = $this->isEmptyFieldValue($valueA);
        $emptyB = $this->isEmptyFieldValue($valueB);
        if ($emptyA && ! $emptyB) {
            return 'b';
        }

        return 'a';
    }

    private function isEmptyFieldValue(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }
        if (is_string($value)) {
            return trim($value) === '';
        }
        if (is_int($value) || is_float($value)) {
            return false;
        }

        return $value === '';
    }

    private function reconcilePlayerImagesAfterMerge(
        int $keepId,
        int $dropId,
        string $oldKeepKey,
        string $oldDropKey,
        string $chosenKey,
    ): void {
        $this->deleteLegacyIdImageFiles($dropId);

        if ($chosenKey === '' || ! AssetKey::isValid($chosenKey)) {
            if (AssetKey::isValid($oldDropKey)) {
                $this->deleteAssetKeyImageFiles($oldDropKey);
            }
            if (AssetKey::isValid($oldKeepKey) && $oldKeepKey !== $chosenKey) {
                // keep may have empty chosen key; leave keep files alone unless abandoned
            }

            return;
        }

        $abandonedKey = $chosenKey === $oldDropKey ? $oldKeepKey : $oldDropKey;
        $sourceKey = $chosenKey === $oldKeepKey ? $oldDropKey : $oldKeepKey;

        if (AssetKey::isValid($sourceKey) && $sourceKey !== $chosenKey) {
            $this->copyAssetKeyImagesWhereMissing($sourceKey, $chosenKey);
        }
        if (AssetKey::isValid($abandonedKey) && $abandonedKey !== $chosenKey) {
            $this->deleteAssetKeyImageFiles($abandonedKey);
        }
        if (AssetKey::isValid($oldDropKey) && $oldDropKey !== $chosenKey) {
            $this->deleteAssetKeyImageFiles($oldDropKey);
        }
    }

    private function copyAssetKeyImagesWhereMissing(string $fromKey, string $toKey): void
    {
        $playersDir = PlayerPicture::playersDir();
        if (! is_dir($playersDir)) {
            return;
        }

        $flatFrom = $playersDir.DIRECTORY_SEPARATOR.$fromKey.'.jpg';
        $flatTo = $playersDir.DIRECTORY_SEPARATOR.$toKey.'.jpg';
        if (is_file($flatFrom) && ! is_file($flatTo)) {
            @copy($flatFrom, $flatTo);
        }

        foreach (glob($playersDir.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.$fromKey.'.jpg') ?: [] as $fromPath) {
            $teamDir = dirname($fromPath);
            $toPath = $teamDir.DIRECTORY_SEPARATOR.$toKey.'.jpg';
            if (! is_file($toPath)) {
                @copy($fromPath, $toPath);
            }
        }
    }

    private function deleteAssetKeyImageFiles(string $assetKey): int
    {
        if (! AssetKey::isValid($assetKey)) {
            return 0;
        }

        $deleted = 0;
        $playersDir = PlayerPicture::playersDir();
        $flat = $playersDir.DIRECTORY_SEPARATOR.$assetKey.'.jpg';
        if (is_file($flat) && @unlink($flat)) {
            $deleted++;
        }
        foreach (glob($playersDir.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.$assetKey.'.jpg') ?: [] as $path) {
            if (is_file($path) && @unlink($path)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    private function deleteLegacyIdImageFiles(int $playerId): int
    {
        if ($playerId <= 0) {
            return 0;
        }

        $deleted = 0;
        $playersDir = PlayerPicture::playersDir();
        $flat = $playersDir.DIRECTORY_SEPARATOR.$playerId.'.jpg';
        if (is_file($flat) && @unlink($flat)) {
            $deleted++;
        }

        if (! is_dir($playersDir)) {
            return $deleted;
        }

        foreach (glob($playersDir.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'*-'.$playerId.'.jpg') ?: [] as $path) {
            if (is_file($path) && @unlink($path)) {
                $deleted++;
            }
        }

        return $deleted;
    }
}
