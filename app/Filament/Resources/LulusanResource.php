<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LulusanResource\Pages;
use App\Models\Lulusan;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LulusanResource extends Resource
{
  protected static ?string $model = Lulusan::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
  protected static ?string $navigationLabel = 'Profil Lulusan';
  protected static string|\UnitEnum|null $navigationGroup = 'Konten Landing Page';
  protected static ?int $navigationSort = 3;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Identitas Lulusan')
        ->schema([
          FileUpload::make('foto')
            ->image()
            ->directory('lulusan/photos')
            ->columnSpanFull(),

          TextInput::make('nama')->required(),
          TextInput::make('angkatan')
            ->placeholder('Angkatan 54 - 2024'),
          TextInput::make('tahun_lulus')
            ->label('Tahun Lulus')
            ->numeric(),
          TextInput::make('instansi_kerja')
            ->label('Instansi Kerja')
            ->placeholder('RS Siloam, TNI AD, dll'),
          TextInput::make('posisi_kerja')
            ->label('Posisi/Jabatan')
            ->placeholder('Analis Kesehatan'),
          Textarea::make('testimoni')
            ->rows(3)
            ->columnSpanFull(),
          Toggle::make('is_active')
            ->label('Tampilkan')
            ->default(true),
        ])
        ->columns(['sm' => 1, 'md' => 2]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('foto')->circular()->size(40)->label(''),
        TextColumn::make('nama')->searchable()->sortable(),
        TextColumn::make('angkatan')->toggleable(),
        TextColumn::make('instansi_kerja')->label('Instansi')->toggleable(),
        IconColumn::make('is_active')->boolean()->label('Tampil'),
      ])
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListLusans::route('/'),
      'create' => Pages\CreateLulusan::route('/create'),
      'edit'   => Pages\EditLulusan::route('/{record}/edit'),
    ];
  }
}
