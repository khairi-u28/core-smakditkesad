<?php
// ============================================================
// FILE: config/cors.php
// This is the COMPLETE file — paste entirely, replacing
// whatever exists (or create new if it doesn't exist)
// ============================================================

return [

  /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

  'paths' => ['api/*', 'sanctum/csrf-cookie'],

  'allowed_methods' => ['*'],

  'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:3001',
    'https://smakditkesad.sch.id',
    'https://kasir.smakditkesad.sch.id',
    // Add your cloudflare tunnel URL here during demo:
    // 'https://abc-def-123.trycloudflare.com',
  ],

  'allowed_origins_patterns' => [],

  'allowed_headers' => ['*'],

  'exposed_headers' => [],

  'max_age' => 0,

  'supports_credentials' => true,

];
