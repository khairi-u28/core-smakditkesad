<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuResource\Pages;
use App\Models\Buku;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BukuResource extends Resource
{
  protected static ?string $model = Buku::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
  protected static ?string $navigationLabel = 'Koleksi Buku';
  protected static string|\UnitEnum|null $navigationGroup = 'E-Library';
  protected static ?int $navigationSort = 1;

  public static function form(Schema $schema): Schema
  {
    return $schema
      ->schema([
      Section::make('Identitas Buku')
        ->schema([
          TextInput::make('judul')
            ->required()
            ->columnSpanFull(),
          TextInput::make('pengarang'),
          TextInput::make('penerbit'),
          TextInput::make('tahun_terbit')
            ->label('Tahun Terbit')
            ->numeric()
            ->minValue(1900)
            ->maxValue(now()->year),
          TextInput::make('isbn')->label('ISBN'),
          Select::make('kategori_id')
            ->label('Kategori')
            ->relationship('kategori', 'nama')
            ->createOptionForm([
              TextInput::make('nama')->required(),
            ])
            ->searchable(),
          Textarea::make('deskripsi')
            ->rows(3)
            ->columnSpanFull(),
        ])
        ->columns(['sm' => 1, 'md' => 2]),

      Section::make('File & Cover')
        ->schema([
          FileUpload::make('cover')
            ->label('Cover Buku')
            ->image()
            ->directory('buku/covers')
            ->imageResizeMode('cover')
            ->imageCropAspectRatio('2:3')
            ->imageResizeTargetWidth('300')
            ->imageResizeTargetHeight('450'),

          FileUpload::make('file_pdf')
            ->label('File PDF')
            ->acceptedFileTypes(['application/pdf'])
            ->directory('buku/pdfs')
            ->maxSize(50 * 1024)
            ->required(fn(string $operation) => $operation === 'create')
            ->helperText('Max 50MB. File diproteksi, tidak bisa didownload langsung.'),

          Toggle::make('is_active')
            ->label('Tersedia untuk Siswa')
            ->default(true),
        ])
        ->columns(['sm' => 1, 'md' => 2]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('cover')
          ->label('')
          ->height(60),
        TextColumn::make('judul')
          ->searchable()
          ->limit(35),
        TextColumn::make('pengarang')
          ->searchable()
          ->toggleable(),
        TextColumn::make('kategori.nama')
          ->label('Kategori')
          ->badge()
          ->toggleable(),
        IconColumn::make('is_active')
          ->label('Tersedia')
          ->boolean(),
      ])
      ->filters([
        SelectFilter::make('kategori_id')
          ->label('Kategori')
          ->relationship('kategori', 'nama'),
        TernaryFilter::make('is_active')->label('Tersedia'),
      ])
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListBukus::route('/'),
      'create' => Pages\CreateBuku::route('/create'),
      'edit'   => Pages\EditBuku::route('/{record}/edit'),
    ];
  }

  public static function getModelLabel(): string
  {
    return 'Buku';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Koleksi Buku';
  }
}
