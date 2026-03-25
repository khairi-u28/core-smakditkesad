<?php
// ============================================================
// FILE: app/Providers/Filament/AdminPanelProvider.php
// Fixed for Filament v5 — removed viteTheme (different in v5)
// ============================================================
namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // ── Branding ──────────────────────────────────
            ->brandName('SMK Analis Kesehatan')
            ->favicon(asset('favicon.ico'))

            // ── Colors ────────────────────────────────────
            ->colors([
                'primary' => Color::Blue,
                'gray'    => Color::Slate,
                'info'    => Color::Cyan,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
            ])

            // ── Font ──────────────────────────────────────
            ->font('Plus Jakarta Sans')

            // ── Navigation Groups ─────────────────────────
            ->navigationGroups([
                NavigationGroup::make('Konten Landing Page')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsed(false),
                NavigationGroup::make('Kasir Lab')
                    ->icon('heroicon-o-beaker')
                    ->collapsed(false),
                NavigationGroup::make('E-Library')
                    ->icon('heroicon-o-book-open')
                    ->collapsed(false),
                NavigationGroup::make('Manajemen Pengguna')
                    ->icon('heroicon-o-users')
                    ->collapsed(true),
            ])

            // ── Resources ─────────────────────────────────
            ->resources([
                \App\Filament\Resources\PostResource::class,
                \App\Filament\Resources\StaffResource::class,
                \App\Filament\Resources\LulusanResource::class,
                \App\Filament\Resources\JenisPemeriksaanResource::class,
                \App\Filament\Resources\PasienResource::class,
                \App\Filament\Resources\StrukResource::class,
                \App\Filament\Resources\BukuResource::class,
                \App\Filament\Resources\KategoriBukuResource::class,
                \App\Filament\Resources\SiswaResource::class,
            ])

            // ── Widgets ───────────────────────────────────
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\RecentStrukWidget::class,
            ])

            // ── Mobile sidebar ────────────────────────────
            ->sidebarCollapsibleOnDesktop()

            // ── Middleware ────────────────────────────────
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
