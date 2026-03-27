<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukResource\Pages;
use App\Models\Struk;
use Filament\Forms\Components\Placeholder;
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

class StrukResource extends Resource
{
  protected static ?string $model = Struk::class;
  protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $navigationLabel = 'Struk Pemeriksaan';
  protected static string|\UnitEnum|null $navigationGroup = 'Lab Asnakes';
  protected static ?int $navigationSort = 1;
  

  public static function form(Schema $schema): Schema
  {
    return $schema->schema([

      // ── 1. Informasi Struk (full-width, 2-col inner grid) ──────────────────
      Section::make('Informasi Struk')
        ->schema([

          // 1.1 Detail Struk
          Section::make('Detail Struk')
            ->schema([
              TextInput::make('kode_struk')
                ->label('No. Struk')
                ->disabled(),
              Placeholder::make('tanggal_pemeriksaan_disp')
                ->label('Tgl Periksa')
                ->content(fn($record) => $record?->tanggal_pemeriksaan?->format('d M Y') ?? '-'),
              Placeholder::make('nama_petugas_disp')
                ->label('Nama Petugas')
                ->content(fn($record) => $record?->siswa?->nama ?? '-'),
              Select::make('status')
                ->label('Status')
                ->options([
                  'draft'            => 'Draft',
                  'menunggu_koreksi' => 'Menunggu Koreksi',
                  'approve'          => 'Approve',
                  'tolak'            => 'Tolak',
                ])
                ->required(),
            ])
            ->columns(1)
            ->columnSpan(1),

          // 1.2 Detail Pasien
          Section::make('Detail Pasien')
            ->schema([
              Placeholder::make('kode_pasien_disp')
                ->label('Kode Pasien')
                ->content(fn($record) => $record?->pasien?->kode_registrasi ?? '-'),
              Placeholder::make('tgl_registrasi_disp')
                ->label('Tgl Registrasi')
                ->content(fn($record) => $record?->pasien?->tanggal_registrasi?->format('d M Y') ?? '-'),
              Placeholder::make('nama_pasien_disp')
                ->label('Nama Pasien')
                ->content(fn($record) => $record?->pasien?->nama_pasien ?? '-'),
              Placeholder::make('kategori_pasien_disp')
                ->label('Kategori Pasien')
                ->content(fn($record) => $record?->pasien?->kategori_pasien ?? '-'),
              Placeholder::make('jenis_kelamin_disp')
                ->label('Gender')
                ->content(fn($record) => $record?->pasien?->jenis_kelamin ?? '-'),
              Placeholder::make('golongan_darah_disp')
                ->label('Gol. Darah')
                ->content(fn($record) => $record?->pasien?->golongan_darah ?? '-'),
              Placeholder::make('no_telepon_disp')
                ->label('No. Telepon')
                ->content(fn($record) => $record?->pasien?->no_telepon ?? '-'),
              Placeholder::make('alamat_disp')
                ->label('Alamat')
                ->content(fn($record) => $record?->pasien?->alamat ?? '-')
                ->columnSpanFull(),
            ])
            ->columns(2)
            ->columnSpan(1),

        ])
        ->columns(2)
        ->columnSpan(['md' => 4]),

      // ── 2. Informasi Persetujuan ────────────────────────────────────────────
      Section::make('Informasi Persetujuan')
        ->schema([
          Placeholder::make('total_tarif_disp')
            ->label('Total Tarif')
            ->content(fn($record) => $record ? 'Rp ' . number_format($record->total_tarif ?? 0, 0, ',', '.') : '-'),
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
        ->columns(1)
        ->columnSpan(['md' => 2]),

      // ── 3. Catatan Koreksi ─────────────────────────────────────────────────
      Section::make('Catatan Koreksi')
        ->collapsed()
        ->schema([
          Textarea::make('catatan_koreksi')
            ->label('Catatan untuk Siswa')
            ->placeholder('Masukkan catatan atau revisi yang diperlukan...')
            ->rows(4)
            ->columnSpanFull(),
        ])
        ->columns(1)
        ->columnSpan(['md' => 2]),

      // ── 4. Hasil Pemeriksaan (full-width) ──────────────────────────────────
      Section::make('Hasil Pemeriksaan')
        ->schema([
          Placeholder::make('pemeriksaans_table')
            ->label('Detail Pemeriksaan')
            ->content(function ($record): \Illuminate\Support\HtmlString {
              $css = '<style>
                .pem-wrap{overflow-x:auto;border-radius:.5rem;border:1px solid #e5e7eb;}
                .dark .pem-wrap{border-color:#374151;}
                .pem-wrap table{width:100%;border-collapse:collapse;}
                .pem-wrap thead tr{background:#eff6ff;border-bottom:2px solid #bfdbfe;}
                .dark .pem-wrap thead tr{background:rgba(30,58,138,.15);border-bottom-color:#1d4ed8;}
                .pem-wrap th{padding:.6rem .875rem;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#1e40af;text-align:left;white-space:nowrap;}
                .dark .pem-wrap th{color:#93c5fd;}
                .pem-wrap th.r,.pem-wrap td.r{text-align:right;}
                .pem-wrap tbody tr{border-bottom:1px solid #f3f4f6;}
                .pem-wrap tbody tr:last-child{border-bottom:none;}
                .dark .pem-wrap tbody tr{border-bottom-color:#1f2937;}
                .pem-wrap td{padding:.6rem .875rem;font-size:.8125rem;color:#374151;vertical-align:middle;}
                .dark .pem-wrap td{color:#d1d5db;}
                .pem-wrap td.b{font-weight:600;color:#111827;}
                .dark .pem-wrap td.b{color:#f9fafb;}
                .pem-wrap td.r{font-weight:500;color:#111827;}
                .dark .pem-wrap td.r{color:#f9fafb;}
                .pem-wrap td.muted{color:#6b7280;font-size:.75rem;}
                .dark .pem-wrap td.muted{color:#9ca3af;}
                .pem-res{font-family:monospace;font-weight:600;color:#2563eb;}
                .dark .pem-res{color:#60a5fa;}
                .pem-nil{color:#9ca3af;}
                .pem-empty{color:#6b7280;font-size:.875rem;text-align:center;padding:1.25rem;border:1px solid #e5e7eb;border-radius:.5rem;background:#f9fafb;}
                .dark .pem-empty{color:#9ca3af;border-color:#374151;background:#111827;}
              </style>';

              if (!$record) {
                return new \Illuminate\Support\HtmlString($css . '<p class="pem-empty">Belum ada data pemeriksaan</p>');
              }

              $items = collect($record->pemeriksaans ?? []);

              if ($items->isEmpty()) {
                return new \Illuminate\Support\HtmlString($css . '<p class="pem-empty">Belum ada data pemeriksaan</p>');
              }

              // Preload JenisPemeriksaan keyed by id for tipe, nilai_normal, satuan lookup
              $jenisMap = \App\Models\JenisPemeriksaan::all()->keyBy('id');

              $rows = $items->map(function ($p) use ($jenisMap) {
                $p      = is_array($p) ? (object) $p : $p;
                $jenis  = $jenisMap->get($p->idPemeriksaan ?? null);

                $bidang       = e($p->bidangPeriksa ?? '-');
                $tipe         = e($jenis?->tipe_periksa ?? '-');
                $nama         = e($p->subPeriksa ?? '-');
                $hasil        = isset($p->hasilPemeriksaan) && $p->hasilPemeriksaan !== null && $p->hasilPemeriksaan !== ''
                  ? '<span class="pem-res">'.e($p->hasilPemeriksaan).'</span>'
                  : '<span class="pem-nil">-</span>';
                $nilaiNormal  = e($jenis?->nilai_normal ?? '-');
                $satuan       = e($jenis?->satuan ?? '-');
                $tarif        = 'Rp ' . number_format($p->tarif ?? 0, 0, ',', '.');

                return "<tr>
                  <td class='b'>{$bidang}</td>
                  <td class='muted'>{$tipe}</td>
                  <td>{$nama}</td>
                  <td>{$hasil}</td>
                  <td class='muted'>{$nilaiNormal}</td>
                  <td class='muted'>{$satuan}</td>
                  <td class='r'>{$tarif}</td>
                </tr>";
              })->implode('');

              $html = $css . "
              <div class='pem-wrap'>
                <table>
                  <thead>
                    <tr>
                      <th>Bidang Periksa</th>
                      <th>Tipe</th>
                      <th>Nama Pemeriksaan</th>
                      <th>Hasil</th>
                      <th>Nilai Normal</th>
                      <th>Satuan</th>
                      <th class='r'>Tarif (Rp)</th>
                    </tr>
                  </thead>
                  <tbody>{$rows}</tbody>
                </table>
              </div>";

              return new \Illuminate\Support\HtmlString($html);
            })
            ->columnSpanFull(),
        ])
        ->columnSpan(['md' => 4]),

    ])->columns(['sm' => 1, 'md' => 4]);
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
