<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Userteam extends Model
{
    protected $table = 'ffb_userteam';

    protected $primaryKey = 'userteam_id';

    public $timestamps = false;

    /**
     * @deprecated Wide columns removed after slot normalization; kept for backfill tooling only.
     *
     * @return list<string>
     */
    public static function playerSlotColumns(): array
    {
        $columns = [];
        for ($i = 1; $i <= 11; $i++) {
            $columns[] = 'userteam_player_id'.$i;
        }

        return $columns;
    }

    public static function hasWideSlotColumns(): bool
    {
        return Schema::hasColumn('ffb_userteam', 'userteam_player_id1');
    }

    public static function hasSlotTable(): bool
    {
        return Schema::hasTable('ffb_userteam_slot');
    }

    /**
     * Playerteam IDs in pitch order (skips empty / zero).
     *
     * @return list<int>
     */
    public function playerteamIdsInSlotOrder(): array
    {
        return $this->slots()
            ->where('userteam_slot_playerteam_id', '>', 0)
            ->orderBy('userteam_slot_slot')
            ->pluck('userteam_slot_playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Replace lineup slots with exactly these playerteam IDs (pitch order).
     *
     * @param  list<int>  $playerteamIds
     */
    public function syncSlots(array $playerteamIds): void
    {
        $userteamId = (int) $this->userteam_id;
        DB::table('ffb_userteam_slot')->where('userteam_slot_userteam_id', $userteamId)->delete();

        $rows = [];
        foreach (array_values($playerteamIds) as $index => $playerteamId) {
            $rows[] = [
                'userteam_slot_userteam_id' => $userteamId,
                'userteam_slot_slot' => $index + 1,
                'userteam_slot_playerteam_id' => (int) $playerteamId,
            ];
        }

        if ($rows !== []) {
            DB::table('ffb_userteam_slot')->insert($rows);
        }
    }

    /**
     * @param  list<int>  $playerteamIds
     * @return Builder<Userteam>
     */
    public static function queryContainingAnyPlayerteam(array $playerteamIds): Builder
    {
        $playerteamIds = array_values(array_filter(array_map('intval', $playerteamIds), static fn (int $id) => $id > 0));
        if ($playerteamIds === []) {
            return self::query()->whereRaw('0 = 1');
        }

        return self::query()->whereHas('slots', function (Builder $q) use ($playerteamIds) {
            $q->whereIn('userteam_slot_playerteam_id', $playerteamIds);
        });
    }

    /**
     * @return list<int>
     */
    public static function playerteamIdsUsedInLineups(): array
    {
        return DB::table('ffb_userteam_slot')
            ->where('userteam_slot_playerteam_id', '>', 0)
            ->distinct()
            ->pluck('userteam_slot_playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function slots(): HasMany
    {
        return $this->hasMany(UserteamSlot::class, 'userteam_slot_userteam_id', 'userteam_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'userteam_user_id', 'user_id');
    }

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'userteam_matchround_id', 'matchround_id');
    }
}
