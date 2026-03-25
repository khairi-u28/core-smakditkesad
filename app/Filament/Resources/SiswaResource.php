<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class SiswaResource extends Resource
{
  protected static ?string $model = Siswa::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationLabel = 'Data Siswa';
  protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Pengguna';
  protected static ?int $navigationSort = 1;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Identitas Siswa')
        ->schema([
          TextInput::make('nis')
            ->label('NIS')
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(20),

          TextInput::make('nama')
            ->label('Nama Lengkap')
            ->required(),

          TextInput::make('kelas')
            ->label('Kelas')
            ->placeholder('XII TLM 1'),

          Select::make('jurusan')
            ->label('Jurusan')
            ->options([
              'Teknologi Laboratorium Medik' => 'Teknologi Laboratorium Medik',
            ])
            ->default('Teknologi Laboratorium Medik'),

          Select::make('jenis_kelamin')
            ->label('Jenis Kelamin')
            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),

          TextInput::make('keterangan')
            ->label('Keterangan'),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Keamanan Akun')
        ->schema([
          TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable()
            ->dehydrateStateUsing(fn($state) => Hash::make($state))
            ->dehydrated(fn($state) => filled($state))
            ->required(fn(string $operation) => $operation === 'create')
            ->helperText('Kosongkan jika tidak ingin mengubah password'),

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
        TextColumn::make('nis')
          ->label('NIS')
          ->searchable()
          ->sortable()
          ->weight('bold'),

        TextColumn::make('nama')
          ->label('Nama')
          ->searchable()
          ->sortable(),

        TextColumn::make('kelas')
          ->label('Kelas')
          ->toggleable(),

        IconColumn::make('is_active')
          ->label('Aktif')
          ->boolean(),

        TextColumn::make('struks_count')
          ->label('Struk')
          ->counts('struks')
          ->toggleable(),
      ])
      ->filters([
        TernaryFilter::make('is_active')->label('Status'),
      ])
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListSiswas::route('/'),
      'create' => Pages\CreateSiswa::route('/create'),
      'edit'   => Pages\EditSiswa::route('/{record}/edit'),
    ];
  }
}
