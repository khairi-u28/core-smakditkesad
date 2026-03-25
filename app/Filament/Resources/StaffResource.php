<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Models\Staff;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaffResource extends Resource
{
  protected static ?string $model = Staff::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
  protected static ?string $navigationLabel = 'Staf & Pengajar';
  protected static string|\UnitEnum|null $navigationGroup = 'Konten Landing Page';
  protected static ?int $navigationSort = 2;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Identitas')
        ->schema([
          FileUpload::make('foto')
            ->label('Foto')
            ->image()
            ->directory('staff/photos')
            ->imageResizeMode('cover')
            ->imageCropAspectRatio('1:1')
            ->imageResizeTargetWidth('400')
            ->imageResizeTargetHeight('400')
            ->columnSpanFull(),

          TextInput::make('nama')->required(),
          TextInput::make('nip')->label('NIP'),
          TextInput::make('jabatan')
            ->required()
            ->placeholder('Kepala Sekolah / Waka Kurikulum'),
          TextInput::make('bidang')
            ->placeholder('Kurikulum / Kesiswaan / Sarpras'),
          TextInput::make('pendidikan')
            ->placeholder('S2, D4, S1'),
          TextInput::make('spesialisasi')
            ->placeholder('Kimia Klinis, Hematologi'),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('Pengaturan Tampilan')
        ->schema([
          Select::make('jenis')
            ->options(['guru' => 'Guru', 'tendik' => 'Tenaga Kependidikan'])
            ->default('guru')
            ->required(),
          TextInput::make('urutan')
            ->label('Urutan Tampil')
            ->numeric()
            ->default(0)
            ->helperText('Angka lebih kecil = tampil lebih dulu'),
          Toggle::make('is_active')
            ->label('Tampilkan')
            ->default(true),
        ])
        ->columns(['sm' => 1, 'md' => 3]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('foto')
          ->label('')
          ->circular()
          ->size(40),
        TextColumn::make('nama')->searchable()->sortable(),
        TextColumn::make('jabatan')->searchable()->toggleable(),
        TextColumn::make('jenis')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'guru'   => 'primary',
            'tendik' => 'warning',
            default  => 'gray',
          }),
        TextColumn::make('urutan')->sortable()->toggleable(),
        IconColumn::make('is_active')->boolean()->label('Tampil'),
      ])
      ->defaultSort('urutan')
      ->reorderable('urutan')
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListStaffs::route('/'),
      'create' => Pages\CreateStaff::route('/create'),
      'edit'   => Pages\EditStaff::route('/{record}/edit'),
    ];
  }
}
