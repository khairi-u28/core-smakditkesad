<?php
// ============================================================
// FILE: app/Filament/Widgets/StatsOverview.php
// Dashboard stats cards — visible on mobile as stacked cards
// ============================================================
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Siswa;
use App\Models\Struk;
use App\Models\Buku;
use App\Models\Post;

class StatsOverview extends BaseWidget
{
  protected static ?int $sort = 1;

  protected function getStats(): array
  {
    return [
      Stat::make('Total Siswa', Siswa::where('is_active', true)->count())
        ->description('Siswa aktif terdaftar')
        ->descriptionIcon('heroicon-m-users')
        ->color('primary'),

      Stat::make('Struk Pending', Struk::where('status', 'submitted')->count())
        ->description('Menunggu koreksi asesor')
        ->descriptionIcon('heroicon-m-document-text')
        ->color('warning'),

      Stat::make('Koleksi Buku', Buku::where('is_active', true)->count())
        ->description('Buku tersedia di E-Library')
        ->descriptionIcon('heroicon-m-book-open')
        ->color('success'),

      Stat::make('Konten Terpublish', Post::where('published', true)->count())
        ->description('Berita, pengumuman, kegiatan')
        ->descriptionIcon('heroicon-m-newspaper')
        ->color('info'),
    ];
  }
}
