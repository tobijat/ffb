<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchGame extends Model
{
    /**
     * Sentinel kickoff time when no real time is known (manual / JSON / missing API time).
     */
    public const DEFAULT_TIME = '11:11:11.111';

    protected $table = 'ffb_match';

    protected $primaryKey = 'match_id';

    public $timestamps = false;

    protected $fillable = [
        'match_round',
        'match_hometeam_id',
        'match_guestteam_id',
        'match_homescore',
        'match_guestscore',
        'match_homescore_penalty',
        'match_guestscore_penalty',
        'match_date',
        'match_minutes',
        'match_status',
        'match_url',
    ];

    /**
     * Build a stored match_date value (Y-m-d H:i:s.v). Date-only inputs get {@see DEFAULT_TIME}.
     */
    public static function composeDateTime(string $dateOrDateTime): string
    {
        $value = trim($dateOrDateTime);
        if (preg_match('/^(\d{4}-\d{2}-\d{2})$/', $value) === 1) {
            return $value.' '.self::DEFAULT_TIME;
        }

        if (preg_match(
            '/^(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2}:\d{2})(\.\d{1,6})?$/',
            $value,
            $m
        ) === 1) {
            $fraction = $m[3] ?? '';
            if ($fraction === '') {
                $fraction = '.000';
            } else {
                $digits = str_pad(substr($fraction, 1, 3), 3, '0');
                $fraction = '.'.$digits;
            }

            return $m[1].' '.$m[2].$fraction;
        }

        if (preg_match('/^(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2})$/', $value, $m) === 1) {
            return $m[1].' '.$m[2].':00.000';
        }

        return $value;
    }

    /**
     * Calendar day (Y-m-d) from a stored or draft match_date value.
     */
    public static function calendarDate(string $dateOrDateTime): string
    {
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', trim($dateOrDateTime), $m) === 1) {
            return $m[1];
        }

        return '';
    }

    /**
     * Whether match_date carries a real kickoff time (not the unknown-time sentinel).
     */
    public static function hasKnownKickoffTime(string $dateOrDateTime): bool
    {
        if (preg_match('/[ T](\d{2}:\d{2}:\d{2})(\.\d{1,6})?/', trim($dateOrDateTime), $m) !== 1) {
            return false;
        }

        $fraction = $m[2] ?? '';
        if ($fraction === '') {
            $fraction = '.000';
        } else {
            $fraction = '.'.str_pad(substr($fraction, 1, 3), 3, '0');
        }

        return ($m[1].$fraction) !== self::DEFAULT_TIME;
    }

    /**
     * Player-facing date label: d.m.Y, plus H:i when kickoff time is known.
     */
    public static function formatDisplayDate(?string $dateOrDateTime): ?string
    {
        if ($dateOrDateTime === null) {
            return null;
        }

        $value = trim($dateOrDateTime);
        $calendar = self::calendarDate($value);
        if ($calendar === '') {
            return null;
        }

        $dateTs = strtotime($calendar);
        if ($dateTs === false) {
            return null;
        }

        $label = date('d.m.Y', $dateTs);
        if (! self::hasKnownKickoffTime($value)) {
            return $label;
        }

        if (preg_match('/[ T](\d{2}:\d{2})/', $value, $m) !== 1) {
            return $label;
        }

        return $label.' '.$m[1];
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'match_hometeam_id', 'team_id');
    }

    public function guestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'match_guestteam_id', 'team_id');
    }

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'match_round', 'matchround_id');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'goal_match_id', 'match_id');
    }

    public function psgoals(): HasMany
    {
        return $this->hasMany(Psgoal::class, 'psgoal_match_id', 'match_id');
    }

    public function playerstats(): HasMany
    {
        return $this->hasMany(Playerstats::class, 'playerstats_match_id', 'match_id');
    }
}
