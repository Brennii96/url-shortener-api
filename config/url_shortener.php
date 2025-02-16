<?php

return [
    'shortened_code_length' => env('SHORTENED_CODE_LENGTH', 6),
    'code_cache_expiration' => env('CODE_CACHE_EXPIRATION', 60 * 24),
];
