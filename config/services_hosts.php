<?php

/**
 * Hostname → application service key.
 *
 * Used to load service-specific routes/UI. Auth credentials always use web_user.
 * Unlisted hosts fall back to "ffb" so existing localhost bookmarks keep working.
 */
return [
    // Production-style hosts
    'soccer.sportsfan.at' => 'ffb',
    'www.sportsfan.at' => 'ffb',
    'pictory.tobijat.at' => 'pictory',

    // Local multi-service emulation (see hosts file + artisan serve / Herd)
    'soccer.test' => 'ffb',
    'pictory.test' => 'pictory',

    // Default local / legacy
    'localhost' => 'ffb',
    '127.0.0.1' => 'ffb',
];
