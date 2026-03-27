<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $navigationLabel = 'Admin';
  protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Pengguna';
  protected static ?int $navigationSort = 1;

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([
      Section::make('Informasi Pengguna')
        ->columns(['sm' => 1, 'md' => 2])
        ->schema([
          TextInput::make('name')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255),

          TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->unique(table: User::class, column: 'email', ignoreRecord: true)
            ->maxLength(255),

          Select::make('user_type')
            ->label('Tipe Pengguna')
            ->options([
              'admin'       => 'Admin',
              'guru'        => 'Guru Koreksi',
              'petugas'     => 'Petugas Lab',
              'pic'         => 'PIC (Penanggung Jawab)',
            ])
            ->required()
            ->native(false),

          Checkbox::make('is_corrector')
            ->label('Dapat Melakukan Koreksi')
            ->helperText('Centang jika pengguna ini bisa mengoreksi Struk pemeriksaan'),
        ]),

      Section::make('Keamanan')
        ->columns(['sm' => 1, 'md' => 2])
        ->schema([
          TextInput::make('password')
            ->label('Password')
            ->password()
            ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
            ->dehydrated(fn($state) => filled($state))
            ->required(fn(string $operation) => $operation === 'create')
            ->helperText('Kosongkan jika tidak ingin mengubah password')
            ->maxLength(255),

          Toggle::make('is_active')
            ->label('Aktif')
            ->default(true),
        ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nama')
          ->searchable()
          ->sortable(),

        TextColumn::make('email')
          ->label('Email')
          ->searchable()
          ->sortable(),

        BadgeColumn::make('user_type')
          ->label('Tipe')
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'admin'   => 'Admin',
            'guru'    => 'Guru Koreksi',
            'petugas' => 'Petugas Lab',
            'pic'     => 'PIC',
            default   => $state,
          })
          ->colors([
            'admin'   => 'danger',
            'guru'    => 'info',
            'petugas' => 'primary',
            'pic'     => 'warning',
          ])
          ->sortable(),

        IconColumn::make('is_corrector')
          ->label('Korreksi')
          ->boolean()
          ->sortable(),

        IconColumn::make('is_active')
          ->label('Aktif')
          ->boolean()
          ->sortable(),
      ])
      ->defaultSort('name')
      ->filters([
        SelectFilter::make('user_type')
          ->label('Tipe Pengguna')
          ->options([
            'admin'   => 'Admin',
            'guru'    => 'Guru Koreksi',
            'petugas' => 'Petugas Lab',
            'pic'     => 'PIC',
          ]),

        TernaryFilter::make('is_active')
          ->label('Status'),

        TernaryFilter::make('is_corrector')
          ->label('Dapat Koreksi'),
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
      'index'  => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit'   => Pages\EditUser::route('/{record}/edit'),
    ];
  }

  public static function getModelLabel(): string
  {
    return 'Pengguna';
  }

  public static function getPluralModelLabel(): string
  {
    return 'Pengguna';
  }
}
