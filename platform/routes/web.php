<?php

use Illuminate\Support\Facades\Route;

/*
| New FFB features land here. Legacy pretty-URLs stay on the root index.php.
| Mounted under /platform/public via Apache (see repo-root .htaccess).
*/

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'FFB Laravel platform',
    ]);
});
