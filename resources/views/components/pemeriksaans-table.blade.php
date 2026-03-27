@php
// Try multiple ways the data might be passed
$items = $viewField ?? ($record?->pemeriksaans ?? []);

// If still empty, try to get from component slot
if (empty($items) && isset($slot)) {
    $items = $slot;
}

// Ensure it's a collection/array
if (!is_iterable($items)) {
    $items = [];
}

$items = collect($items)->all();
@endphp

@if (empty($items) || count($items) === 0)
  <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-center text-sm text-gray-600">
    Belum ada data pemeriksaan
  </div>
@else
  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="w-full">
      <!-- TABLE HEADER - Only appears once -->
      <thead>
        <tr class="border-b border-gray-200 bg-blue-50">
          <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-900">
            Bidang Periksa
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-900">
            Sub Pemeriksaan
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-900">
            Hasil
          </th>
          <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-blue-900">
            Tarif (Rp)
          </th>
        </tr>
      </thead>

      <!-- TABLE BODY - Data rows repeat -->
      <tbody class="divide-y divide-gray-100">
        @foreach ($items as $item)
          <tr class="transition-colors hover:bg-blue-50/50">
            <td class="px-4 py-3 text-sm font-medium text-gray-800">
              @if (is_array($item))
                {{ e($item['bidangPeriksa'] ?? '-') }}
              @else
                {{ e($item->bidangPeriksa ?? '-') }}
              @endif
            </td>
            <td class="px-4 py-3 text-sm text-gray-700">
              @if (is_array($item))
                {{ e($item['subPeriksa'] ?? '-') }}
              @else
                {{ e($item->subPeriksa ?? '-') }}
              @endif
            </td>
            <td class="px-4 py-3 text-sm text-gray-700">
              @if (is_array($item))
                @if ($item['hasilPemeriksaan'] ?? null)
                  <span class="font-mono text-blue-600">{{ e($item['hasilPemeriksaan']) }}</span>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              @else
                @if ($item->hasilPemeriksaan ?? null)
                  <span class="font-mono text-blue-600">{{ e($item->hasilPemeriksaan) }}</span>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              @endif
            </td>
            <td class="px-4 py-3 text-right text-sm font-medium text-gray-800">
              @if (is_array($item))
                Rp {{ number_format($item['tarif'] ?? 0, 0, ',', '.') }}
              @else
                Rp {{ number_format($item->tarif ?? 0, 0, ',', '.') }}
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif
