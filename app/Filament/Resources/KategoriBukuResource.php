<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriBukuResource\Pages;
use App\Models\KategoriBuku;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KategoriBukuResource extends Resource
{
  protected static ?string $model = KategoriBuku::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';
  protected static ?string $navigationLabel = 'Kategori Buku';
  protected static string|\UnitEnum|null $navigationGroup = 'E-Library';
  protected static ?int $navigationSort = 2;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make()
        ->schema([
          TextInput::make('nama')
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(fn(string $state, Set $set) => $set('slug', Str::slug($state))),
          TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true),
        ])
        ->columns(2),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('nama')->searchable()->sortable(),
        TextColumn::make('slug')->searchable()->toggleable(),
        TextColumn::make('buku_count')
          ->label('Jumlah Buku')
          ->counts('buku'),
      ])
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListKategoriBukus::route('/'),
      'create' => Pages\CreateKategoriBuku::route('/create'),
      'edit'   => Pages\EditKategoriBuku::route('/{record}/edit'),
    ];
  }

  public static function getModelLabel(): string
  {
    return 'Kategori Buku';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Kategori Buku';
  }
}
