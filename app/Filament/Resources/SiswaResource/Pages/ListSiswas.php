<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_template')
                ->label('📥 Template CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn() => $this->downloadTemplate()),

            Actions\Action::make('import_csv')
                ->label('📤 Import Data')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Upload CSV File')
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(fn(array $data) => $this->importCSV($data['csv_file'])),

            Actions\CreateAction::make(),
        ];
    }

    public function downloadTemplate()
    {
        $fileName = 'siswa_template_' . now()->format('Ymd_His') . '.csv';
        $headers = ['NIS', 'Nama', 'Kelas', 'Jurusan', 'Jenis Kelamin', 'Keterangan'];
        
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, $headers);
        fputcsv($handle, ['12001', 'Contoh Nama', 'XII TLM 1', 'Teknologi Laboratorium Medik', 'L', 'Catatan']);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            function() use ($csv) {
                echo $csv;
            },
            $fileName,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$fileName\"",
            ]
        );
    }

    public function importCSV(string $filePath): void
    {
        try {
            // Try to read file using Storage facade first
            $csvContent = null;
            
            // Try multiple disk locations
            $disks = ['local', 'public'];
            $possiblePaths = [
                'livewire-tmp/' . $filePath,
                $filePath,
            ];

            foreach ($disks as $disk) {
                foreach ($possiblePaths as $path) {
                    try {
                        if (Storage::disk($disk)->exists($path)) {
                            $csvContent = Storage::disk($disk)->get($path);
                            $actualPath = Storage::disk($disk)->path($path);
                            break 2;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            // Fallback: try direct file system paths
            if ($csvContent === null) {
                $filePaths = [
                    storage_path('app/livewire-tmp/' . $filePath),
                    storage_path('app/' . $filePath),
                    $filePath,
                ];

                foreach ($filePaths as $path) {
                    if (file_exists($path)) {
                        $csvContent = file_get_contents($path);
                        $actualPath = $path;
                        break;
                    }
                }
            }

            if ($csvContent === null) {
                Notification::make()
                    ->danger()
                    ->title('File tidak ditemukan')
                    ->body('File CSV tidak dapat diakses.')
                    ->send();
                return;
            }

            // Parse CSV from content
            $lines = explode("\n", $csvContent);
            $header = null;
            
            $imported = 0;
            $failed = 0;
            $failedRows = [];

            foreach ($lines as $index => $line) {
                if (empty(trim($line))) continue;

                $row = str_getcsv($line);
                
                if ($index === 0 || $header === null) {
                    $header = $row;
                    continue;
                }

                try {
                    if (empty($row[0]) || count($row) < 2) continue;

                    $nis = trim($row[0]);
                    $nama = trim($row[1]);
                    $kelas = isset($row[2]) ? trim($row[2]) : null;
                    $jurusan = isset($row[3]) ? trim($row[3]) : 'Teknologi Laboratorium Medik';
                    $jenis_kelamin = isset($row[4]) ? trim($row[4]) : null;
                    $keterangan = isset($row[5]) ? trim($row[5]) : null;

                    // Check if NIS already exists
                    if (Siswa::where('nis', $nis)->exists()) {
                        $failed++;
                        $failedRows[] = "NIS '$nis' sudah ada";
                        continue;
                    }

                    Siswa::create([
                        'nis' => $nis,
                        'nama' => $nama,
                        'kelas' => $kelas,
                        'jurusan' => $jurusan,
                        'jenis_kelamin' => $jenis_kelamin,
                        'keterangan' => $keterangan,
                        'password' => Hash::make($nis),
                        'is_active' => true,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                    $failedRows[] = "Baris gagal: " . $e->getMessage();
                }
            }

            $message = "$imported siswa berhasil diimport.";
            if ($failed > 0) {
                $message .= " $failed gagal.";
                if (count($failedRows) <= 5) {
                    $message .= " " . implode("; ", $failedRows);
                }
            }

            Notification::make()
                ->success()
                ->title('Import Selesai')
                ->body($message)
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error saat import')
                ->body($e->getMessage())
                ->send();
        }
    }
}
