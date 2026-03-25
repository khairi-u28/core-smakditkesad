<?php
// ============================================================
// FILE: routes/api.php
// Complete API routes for all three frontend apps
// ============================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\LulusanController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\KasirController;

Route::prefix('v1')->group(function () {

    // ── PUBLIC (Landing Page — no auth needed) ─────────────
    Route::get('/posts',         [PostController::class,   'index']);
    Route::get('/posts/{slug}',  [PostController::class,   'show']);
    Route::get('/staff',         [StaffController::class,  'index']);
    Route::get('/pages/{slug}',  [PageController::class,   'show']);
    Route::get('/lulusan',       [LulusanController::class, 'index']);

    // ── AUTH ───────────────────────────────────────────────
    Route::post('/auth/login',   [AuthController::class,   'login']);

    // ── PROTECTED (Kasir Lab + E-Library) ─────────────────
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        // ── E-Library ─────────────────────────
        Route::get('/buku',               [BukuController::class, 'index']);
        Route::get('/buku/{id}',          [BukuController::class, 'show']);
        Route::get('/buku/{id}/file',     [BukuController::class, 'streamPdf']);

        // ── Kasir Lab ─────────────────────────
        Route::prefix('kasir')->group(function () {
            // Master catalog (read-only for siswa)
            Route::get('/paket',                   [KasirController::class, 'paketIndex']);

            // Pasien
            Route::get('/pasien',                  [KasirController::class, 'pasienIndex']);
            Route::post('/pasien',                 [KasirController::class, 'pasienStore']);

            // Struk (transaction)
            Route::get('/struk',                   [KasirController::class, 'strukIndex']);
            Route::post('/struk',                  [KasirController::class, 'strukStore']);
            Route::put('/struk/{id}',              [KasirController::class, 'strukUpdate']);
            Route::post('/struk/{id}/submit',      [KasirController::class, 'strukSubmit']);

            // Hasil Lab (after asesor approves)
            Route::get('/hasil',                   [KasirController::class, 'hasilIndex']);
            Route::get('/hasil/{id}',              [KasirController::class, 'hasilShow']);
        });
    });
});
