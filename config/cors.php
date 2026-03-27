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
  ],

  // Wildcard patterns for local tunneling tools (remove in strict prod)
  'allowed_origins_patterns' => [
    '#^https://[a-z0-9\-]+\.loca\.lt$#',          // localtunnel
    '#^https://[a-z0-9\-]+\.ngrok-free\.app$#',   // ngrok free tier
    '#^https://[a-z0-9\-]+\.ngrok\.io$#',         // ngrok paid
    '#^https://[a-z0-9\-]+\.trycloudflare\.com$#', // Cloudflare Tunnel
  ],

  'allowed_headers' => ['*'],

  'exposed_headers' => [],

  'max_age' => 0,

  'supports_credentials' => true,

];
