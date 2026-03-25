<?php
// ============================================================
// FILE: app/Filament/Widgets/RecentStrukWidget.php
// Fixed for Filament v5
// ============================================================
namespace App\Filament\Widgets;

use App\Filament\Resources\StrukResource;
use App\Models\Struk;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStrukWidget extends BaseWidget
{
  protected static ?int $sort = 2;
  protected int|string|array $columnSpan = 'full';
  protected static ?string $heading = 'Struk Menunggu Koreksi';

  public function table(Table $table): Table
  {
    return $table
      ->query(Struk::where('status', 'submitted')->latest())
      ->columns([
        TextColumn::make('kode_struk')->label('Kode'),
        TextColumn::make('siswa.nama')->label('Siswa'),
        TextColumn::make('pasien.nama_pasien')->label('Pasien'),
        TextColumn::make('total_tarif')->money('IDR')->label('Total'),
        TextColumn::make('submitted_at')->since()->label('Dikirim'),
      ])
      ->actions([
        Action::make('koreksi')
          ->label('Koreksi')
          ->icon('heroicon-o-pencil-square')
          ->color('warning')
          ->url(fn($record) => StrukResource::getUrl('edit', ['record' => $record])),
      ]);
  }
}
