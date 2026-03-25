<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Models\Pasien;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PasienResource extends Resource
{
  protected static ?string $model = Pasien::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $navigationLabel = 'Data Pasien';
  protected static string|\UnitEnum|null $navigationGroup = 'Kasir Lab';
  protected static ?int $navigationSort = 2;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Data Pasien')
        ->schema([
          TextInput::make('kode_registrasi')
            ->label('Kode Registrasi')
            ->disabled()
            ->helperText('Otomatis dibuat saat siswa mendaftar'),

          Select::make('siswa_id')
            ->label('Didaftarkan oleh')
            ->relationship('siswa', 'nama')
            ->searchable()
            ->required(),

          TextInput::make('nama_pasien')->required(),

          Select::make('kategori_pasien')
            ->options(['Umum' => 'Umum', 'BPJS' => 'BPJS', 'Asuransi Lain' => 'Asuransi Lain'])
            ->required(),

          Select::make('jenis_kelamin')
            ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
            ->required(),

          Select::make('golongan_darah')
            ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O', '-' => 'Tidak Tahu'])
            ->default('-'),

          Select::make('status_pernikahan')
            ->options(['Belum Menikah' => 'Belum Menikah', 'Menikah' => 'Menikah', 'Cerai' => 'Cerai']),

          TextInput::make('no_telepon'),
          TextInput::make('pekerjaan'),
          TextInput::make('no_kk')->label('No. KK'),
          TextInput::make('nama_ayah'),
          TextInput::make('nama_ibu'),
          TextInput::make('dokter_pengirim'),

          Textarea::make('alamat')
            ->columnSpanFull()
            ->rows(2),
        ])
        ->columns(['sm' => 1, 'md' => 2]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('kode_registrasi')
          ->label('Kode')
          ->searchable()
          ->copyable(),
        TextColumn::make('nama_pasien')
          ->label('Nama Pasien')
          ->searchable()
          ->sortable(),
        TextColumn::make('kategori_pasien')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'Umum'          => 'primary',
            'BPJS'          => 'success',
            'Asuransi Lain' => 'warning',
            default         => 'gray',
          }),
        TextColumn::make('siswa.nama')
          ->label('Didaftarkan Oleh')
          ->toggleable(),
        TextColumn::make('tanggal_registrasi')
          ->label('Tgl Registrasi')
          ->date('d M Y')
          ->sortable(),
        TextColumn::make('created_at')
          ->label('Waktu Pendaftaran')
          ->dateTime('d M Y H:i:s')
          ->sortable(),
      ])
      ->defaultSort('tanggal_registrasi', 'desc')
      ->filters([
        SelectFilter::make('kategori_pasien')
          ->options(['Umum' => 'Umum', 'BPJS' => 'BPJS', 'Asuransi Lain' => 'Asuransi Lain']),
        SelectFilter::make('siswa_id')
          ->label('Siswa')
          ->relationship('siswa', 'nama'),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListPasiens::route('/'),
      'create' => Pages\CreatePasien::route('/create'),
      'edit'   => Pages\EditPasien::route('/{record}/edit'),
    ];
  }

  public static function getModelLabel(): string
  {
    return 'Pasien';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Data Pasien';
  }
}
