<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukResource\Pages;
use App\Models\Struk;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class StrukResource extends Resource
{
  protected static ?string $model = Struk::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $navigationLabel = 'Struk Pemeriksaan';
  protected static string|\UnitEnum|null $navigationGroup = 'Kasir Lab';
  protected static ?int $navigationSort = 3;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Info Struk')
        ->schema([
          TextInput::make('kode_struk')
            ->label('Kode Struk')
            ->disabled(),

          Select::make('siswa_id')
            ->label('Siswa')
            ->relationship('siswa', 'nama')
            ->disabled(),

          Select::make('pasien_id')
            ->label('Pasien')
            ->relationship('pasien', 'nama_pasien')
            ->disabled(),

          Select::make('status')
            ->options([
              'draft'     => 'Draft',
              'submitted' => 'Menunggu Koreksi',
              'dikoreksi' => 'Sedang Dikoreksi',
              'selesai'   => 'Selesai',
            ])
            ->required(),

          TextInput::make('total_tarif')
            ->label('Total Tarif')
            ->prefix('Rp')
            ->disabled(),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Hasil Pemeriksaan')
        ->schema([
          Placeholder::make('pemeriksaans_view')
            ->label('')
            ->content(function ($record): HtmlString|string {
              if (!$record || !$record->pemeriksaans) {
                return 'Belum ada data';
              }
              $html  = '<div class="overflow-x-auto">';
              $html .= '<table class="w-full text-sm">';
              $html .= '<thead><tr class="bg-gray-100">';
              $html .= '<th class="p-2 text-left">Pemeriksaan</th>';
              $html .= '<th class="p-2 text-left">Hasil</th>';
              $html .= '<th class="p-2 text-right">Tarif</th>';
              $html .= '</tr></thead><tbody>';
              foreach ($record->pemeriksaans as $p) {
                $hasil  = $p['hasilPemeriksaan'] ?? null;
                $hasil  = $hasil ? e($hasil) : '<span class="text-gray-400">-</span>';
                $tarif  = number_format($p['tarif'] ?? 0, 0, ',', '.');
                $html  .= '<tr class="border-b">';
                $html  .= '<td class="p-2">' . e($p['subPeriksa'] ?? '-') . '</td>';
                $html  .= '<td class="p-2 font-mono">' . $hasil . '</td>';
                $html  .= '<td class="p-2 text-right">Rp ' . $tarif . '</td>';
                $html  .= '</tr>';
              }
              $html .= '</tbody></table></div>';
              return new HtmlString($html);
            }),
        ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('kode_struk')
          ->label('Kode')
          ->searchable()
          ->weight('bold'),
        TextColumn::make('siswa.nama')
          ->label('Siswa')
          ->searchable(),
        TextColumn::make('pasien.nama_pasien')
          ->label('Pasien')
          ->searchable(),
        TextColumn::make('status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'draft'     => 'gray',
            'submitted' => 'warning',
            'dikoreksi' => 'primary',
            'selesai'   => 'success',
            default     => 'gray',
          }),
        TextColumn::make('total_tarif')
          ->label('Total')
          ->money('IDR'),
        TextColumn::make('tanggal_pemeriksaan')
          ->label('Tanggal')
          ->date('d M Y')
          ->sortable(),
      ])
      ->defaultSort('tanggal_pemeriksaan', 'desc')
      ->filters([
        SelectFilter::make('status')
          ->options([
            'draft'     => 'Draft',
            'submitted' => 'Menunggu',
            'dikoreksi' => 'Dikoreksi',
            'selesai'   => 'Selesai',
          ]),
      ])
      ->actions([
        Action::make('koreksi')
          ->label('Koreksi')
          ->icon('heroicon-o-pencil-square')
          ->color('warning')
          ->visible(fn($record) => $record->status === 'submitted')
          ->url(fn($record) => StrukResource::getUrl('edit', ['record' => $record])),
        ViewAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListStruks::route('/'),
      'create' => Pages\CreateStruk::route('/create'),
      'edit'   => Pages\EditStruk::route('/{record}/edit'),
      'view'   => Pages\ViewStruk::route('/{record}'),
    ];
  }
}
