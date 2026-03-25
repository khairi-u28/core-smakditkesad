<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukResource\Pages;
use App\Models\Struk;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
  protected static string|\UnitEnum|null $navigationGroup = 'Laboratorium';
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
              'draft'            => 'Draft',
              'menunggu_koreksi' => 'Menunggu Koreksi',
              'approve'          => 'Approve',
              'tolak'            => 'Tolak',
            ])
            ->required(),

          TextInput::make('total_tarif')
            ->label('Total Tarif')
            ->prefix('Rp')
            ->disabled(),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Informasi Persetujuan')
        ->schema([
          Placeholder::make('approved_at_display')
            ->label('Tanggal Persetujuan')
            ->content(function ($record) {
              return $record?->approved_at 
                ? $record->approved_at->format('d M Y H:i:s')
                : '(belum disetujui)';
            }),

          Placeholder::make('approved_by_display')
            ->label('Disetujui Oleh')
            ->content(function ($record) {
              return $record?->approvedBy?->name ?? '(belum disetujui)';
            }),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Catatan Koreksi')
        ->collapsed()
        ->schema([
          Textarea::make('catatan_koreksi')
            ->label('Catatan untuk Siswa')
            ->placeholder('Masukkan catatan atau revisi yang diperlukan...')
            ->rows(4)
            ->columnSpanFull()
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Hasil Pemeriksaan')
        ->schema([
          Placeholder::make('pemeriksaans_view')
            ->label('')
            ->content(function ($record): HtmlString|string {
              if (!$record || !$record->pemeriksaans) {
                return 'Belum ada data pemeriksaan';
              }
              return new HtmlString(view('components.struk-hasil-pemeriksaan', ['record' => $record])->render());
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
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'draft'            => 'Draft',
            'menunggu_koreksi' => 'Menunggu Koreksi',
            'approve'          => 'Approve',
            'tolak'            => 'Tolak',
            default            => $state,
          })
          ->color(fn(string $state): string => match ($state) {
            'draft'            => 'gray',
            'menunggu_koreksi' => 'warning',
            'approve'          => 'success',
            'tolak'            => 'danger',
            default            => 'gray',
          }),
        TextColumn::make('total_tarif')
          ->label('Total')
          ->money('IDR'),
        TextColumn::make('tanggal_pemeriksaan')
          ->label('Tanggal')
          ->date('d M Y')
          ->sortable(),
        TextColumn::make('approved_at')
          ->label('Tgl Persetujuan')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->placeholder('-'),
        TextColumn::make('approvedBy.name')
          ->label('Disetujui Oleh')
          ->sortable()
          ->placeholder('-'),
      ])
      ->defaultSort('tanggal_pemeriksaan', 'desc')
      ->filters([
        SelectFilter::make('status')
          ->options([
            'draft'            => 'Draft',
            'menunggu_koreksi' => 'Menunggu Koreksi',
            'approve'          => 'Approve',
            'tolak'            => 'Tolak',
          ]),
      ])
      ->actions([
        Action::make('koreksi')
          ->label('Koreksi')
          ->icon('heroicon-o-pencil-square')
          ->color('warning')
          ->visible(fn($record) => $record->status === 'menunggu_koreksi')
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

  public static function getModelLabel(): string
  {
    return 'Struk';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Struk Pemeriksaan';
  }
}
