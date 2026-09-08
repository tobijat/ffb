<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Userteam extends Model
{
    protected $table = 'ffb_userteam';

    protected $primaryKey = 'userteam_id';

    public $timestamps = false;

    /**
     * Slot column names in lineup order (1..11).
     *
     * @return list<string>
     */
    public static function playerSlotColumns(): array
    {
        $columns = [];
        for ($i = 1; $i <= 11; $i++) {
            $columns[] = 'userteam_player_id' . $i;
        }

        return $columns;
    }

    /**
     * Playerteam IDs in slot order.
     *
     * @return list<int>
     */
    public function playerteamIdsInSlotOrder(): array
    {
        $ids = [];
        foreach (self::playerSlotColumns() as $column) {
            $id = (int) $this->getAttribute($column);
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return $ids;
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
