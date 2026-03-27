@php
// ViewField from Filament passes array of items directly
// For ViewField usage: $viewField contains the array of pemeriksaans
// For Blade component usage: $record contains the full model

if (isset($viewField)) {
    // When used as ViewField in Filament forms
    $pemeriksaans = $viewField;
    $recordData = [];
} else {
    // When used as a legacy Blade component
    $pemeriksaans = $record?->pemeriksaans ?? [];
    $recordData = $record;
}

// Group by bidangPeriksa
$grouped = collect($pemeriksaans)->groupBy(function($item) {
    return is_array($item) ? ($item['bidangPeriksa'] ?? 'Tidak Ditentukan') : ($item->bidangPeriksa ?? 'Tidak Ditentukan');
});
@endphp

@if (empty($pemeriksaans) || $grouped->isEmpty())
  <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-center text-sm text-gray-600">
    Belum ada data pemeriksaan
  </div>
@else
  <div class="space-y-4">
    @foreach ($grouped as $bidang => $items)
      <div class="overflow-hidden rounded-lg border border-gray-200">
        {{-- Group Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2">
          <h3 class="text-sm font-semibold text-white">{{ $bidang }}</h3>
        </div>

        {{-- Table --}}
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
              <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Sub Pemeriksaan
              </th>
              <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Hasil
              </th>
              <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                Tarif
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($items as $p)
              <tr class="transition-colors hover:bg-blue-50/50">
                <td class="px-4 py-2 text-sm font-medium text-gray-800">
                  @if (is_array($p))
                    {{ e($p['subPeriksa'] ?? '-') }}
                  @else
                    {{ e($p->subPeriksa ?? '-') }}
                  @endif
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                  @if (is_array($p))
                    @if ($p['hasilPemeriksaan'] ?? null)
                      <span class="font-mono">{{ e($p['hasilPemeriksaan']) }}</span>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  @else
                    @if ($p->hasilPemeriksaan ?? null)
                      <span class="font-mono">{{ e($p->hasilPemeriksaan) }}</span>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  @endif
                </td>
                <td class="px-4 py-2 text-right text-sm text-gray-700">
                  @if (is_array($p))
                    Rp {{ number_format($p['tarif'] ?? 0, 0, ',', '.') }}
                  @else
                    Rp {{ number_format($p->tarif ?? 0, 0, ',', '.') }}
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endforeach

    {{-- Total Footer --}}
    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
      <div class="flex items-center justify-between">
        <span class="text-sm font-semibold text-gray-700">Total Tarif:</span>
        <span class="text-lg font-bold text-blue-600">
          Rp {{ number_format($record?->total_tarif ?? 0, 0, ',', '.') }}
        </span>
      </div>
    </div>
  </div>
@endif
