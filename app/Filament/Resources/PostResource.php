<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
  protected static ?string $model = Post::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';
  protected static ?string $navigationLabel = 'Berita & Kegiatan';
  protected static string|\UnitEnum|null $navigationGroup = 'Konten Landing Page';
  protected static ?int $navigationSort = 1;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make()
        ->schema([
          TextInput::make('judul')
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(function (string $state, Set $set) {
              $set('slug', Str::slug($state));
            })
            ->columnSpanFull(),

          TextInput::make('slug')
            ->required()
            ->unique(ignoreRecord: true)
            ->helperText('URL-friendly, otomatis terisi dari judul'),

          Select::make('kategori')
            ->options([
              'berita'     => 'Berita',
              'pengumuman' => 'Pengumuman',
              'kegiatan'   => 'Kegiatan',
            ])
            ->required(),

          TextInput::make('penulis')->label('Penulis'),

          FileUpload::make('thumbnail')
            ->label('Foto Utama')
            ->image()
            ->directory('posts/thumbnails')
            ->columnSpanFull(),

          RichEditor::make('konten')
            ->label('Isi Konten')
            ->toolbarButtons([
              'bold',
              'italic',
              'underline',
              'bulletList',
              'orderedList',
              'h2',
              'h3',
              'link',
              'blockquote',
            ])
            ->columnSpanFull(),

          Toggle::make('published')
            ->label('Publikasikan')
            ->live()
            ->afterStateUpdated(function (bool $state, Set $set) {
              if ($state) $set('published_at', now());
            }),

          DateTimePicker::make('published_at')
            ->label('Tanggal Publikasi')
            ->visible(fn(Get $get) => $get('published')),
        ])
        ->columns(['sm' => 1, 'md' => 2]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('thumbnail')
          ->label('')
          ->size(50),
        TextColumn::make('judul')
          ->searchable()
          ->limit(40)
          ->tooltip(fn($record) => $record->judul),
        TextColumn::make('kategori')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'berita'     => 'primary',
            'pengumuman' => 'warning',
            'kegiatan'   => 'success',
            default      => 'gray',
          }),
        IconColumn::make('published')
          ->label('Publik')
          ->boolean(),
        TextColumn::make('published_at')
          ->label('Tanggal')
          ->date('d M Y')
          ->sortable()
          ->toggleable(),
      ])
      ->defaultSort('published_at', 'desc')
      ->filters([
        SelectFilter::make('kategori')
          ->options(['berita' => 'Berita', 'pengumuman' => 'Pengumuman', 'kegiatan' => 'Kegiatan']),
        TernaryFilter::make('published')->label('Status Publikasi'),
      ])
      ->actions([
        EditAction::make()->iconButton(),
        DeleteAction::make()->iconButton(),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListPosts::route('/'),
      'create' => Pages\CreatePost::route('/create'),
      'edit'   => Pages\EditPost::route('/{record}/edit'),
    ];
  }
}
