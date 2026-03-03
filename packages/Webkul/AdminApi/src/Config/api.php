<?php

return [
    'access_token_ttl'  => intval(env('ACCESS_TOKEN_TTL', 3600)),
    'refresh_token_ttl' => intval(env('REFRESH_TOKEN_TTL', 3600)),
];
