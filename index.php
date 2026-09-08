<?php

/**
 * Fallback front controller when the vhost DocumentRoot is the repo root
 * and mod_rewrite is unavailable. Prefer pointing DocumentRoot at /public.
 */
require __DIR__.'/public/index.php';
