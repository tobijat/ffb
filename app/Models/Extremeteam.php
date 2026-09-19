<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Extremeteam extends Model
{
    protected $table = 'ffb_extremeteam';

    protected $primaryKey = 'extremeteam_id';

    public $timestamps = false;

    protected $fillable = [
        'extremeteam_top_or_flop',
        'extremeteam_price',
        'extremeteam_matchround_id',
        'extremeteam_score',
    ];

    /**
     * Playerteam IDs in pitch order (skips empty / zero).
     *
     * @return list<int>
     */
    public function playerteamIdsInSlotOrder(): array
    {
        return $this->slots()
            ->where('extremeteam_slot_playerteam_id', '>', 0)
            ->orderBy('extremeteam_slot_slot')
            ->pluck('extremeteam_slot_playerteam_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Replace slots with exactly these playerteam IDs (pitch order).
     *
     * @param  list<int>  $playerteamIds
     */
    public function syncSlots(array $playerteamIds): void
    {
        $extremeteamId = (int) $this->extremeteam_id;
        DB::table('ffb_extremeteam_slot')->where('extremeteam_slot_extremeteam_id', $extremeteamId)->delete();

        $rows = [];
        foreach (array_values($playerteamIds) as $index => $playerteamId) {
            $rows[] = [
                'extremeteam_slot_extremeteam_id' => $extremeteamId,
                'extremeteam_slot_slot' => $index + 1,
                'extremeteam_slot_playerteam_id' => (int) $playerteamId,
            ];
        }

        if ($rows !== []) {
            DB::table('ffb_extremeteam_slot')->insert($rows);
        }
    }

    public function slots(): HasMany
    {
        return $this->hasMany(ExtremeteamSlot::class, 'extremeteam_slot_extremeteam_id', 'extremeteam_id');
    }

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'extremeteam_matchround_id', 'matchround_id');
    }
}
