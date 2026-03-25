<?php
// ============================================================
// FILE: app/Filament/Resources/JenisPemeriksaanResource.php
// ============================================================
namespace App\Filament\Resources;

use App\Filament\Resources\JenisPemeriksaanResource\Pages;
use App\Models\JenisPemeriksaan;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class JenisPemeriksaanResource extends Resource
{
  protected static ?string $model = JenisPemeriksaan::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';
  protected static ?string $navigationLabel = 'Jenis Pemeriksaan';
  protected static string|\UnitEnum|null $navigationGroup = 'Kasir Lab';
  protected static ?int $navigationSort = 1;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Identitas Pemeriksaan')
        ->schema([
          TextInput::make('kode')
            ->label('Kode')
            ->required()
            ->unique(ignoreRecord: true)
            ->helperText('Contoh: 1, 2, HMT001'),

          Select::make('bidang_periksa')
            ->label('Bidang')
            ->options([
              'Hematologi'    => 'Hematologi',
              'Kimia Klinik'  => 'Kimia Klinik',
              'Urinalisis'    => 'Urinalisis',
              'Imunoserologi' => 'Imunoserologi',
              'Mikrobiologi'  => 'Mikrobiologi',
              'Parasitologi'  => 'Parasitologi',
            ])
            ->required()
            ->searchable(),

          TextInput::make('tipe_periksa')
            ->label('Tipe')
            ->required()
            ->placeholder('Darah Lengkap, Fungsi Hati'),

          TextInput::make('sub_periksa')
            ->label('Nama Pemeriksaan')
            ->required()
            ->placeholder('Hemoglobin, Leukosit'),

          TextInput::make('nilai_normal')
            ->label('Nilai Normal')
            ->placeholder('12.0-14.0(P) 13.0-16.0(L)'),

          TextInput::make('satuan')
            ->label('Satuan')
            ->placeholder('g/dL, ribu/µL, %'),

          TextInput::make('tarif')
            ->label('Tarif (Rp)')
            ->numeric()
            ->prefix('Rp')
            ->required(),

          Toggle::make('is_active')
            ->label('Aktif')
            ->default(true),
        ])
        ->columns(['sm' => 1, 'md' => 2]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('kode')->label('Kode')->sortable(),
        TextColumn::make('bidang_periksa')
          ->label('Bidang')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'Hematologi'    => 'danger',
            'Kimia Klinik'  => 'warning',
            'Urinalisis'    => 'primary',
            'Imunoserologi' => 'success',
            default         => 'gray',
          }),
        TextColumn::make('sub_periksa')->label('Nama Test')->searchable(),
        TextColumn::make('nilai_normal')->label('Normal')->toggleable(),
        TextColumn::make('satuan')->toggleable(),
        TextColumn::make('tarif')
          ->label('Tarif')
          ->money('IDR')
          ->sortable(),
        IconColumn::make('is_active')->boolean()->label('Aktif'),
      ])
      ->groups([
        Group::make('bidang_periksa')->label('Bidang'),
      ])
      ->defaultGroup('bidang_periksa')
      ->filters([
        SelectFilter::make('bidang_periksa')
          ->options([
            'Hematologi'    => 'Hematologi',
            'Kimia Klinik'  => 'Kimia Klinik',
            'Urinalisis'    => 'Urinalisis',
            'Imunoserologi' => 'Imunoserologi',
          ]),
      ])
      ->actions([
        EditAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListJenisPemeriksaans::route('/'),
      'create' => Pages\CreateJenisPemeriksaan::route('/create'),
      'edit'   => Pages\EditJenisPemeriksaan::route('/{record}/edit'),
    ];
  }

  public static function getModelLabel(): string
  {
    return 'Jenis Pemeriksaan';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Jenis Pemeriksaan';
  }
}
