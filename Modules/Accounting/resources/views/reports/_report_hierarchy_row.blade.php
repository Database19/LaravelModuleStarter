@foreach($items as $item)
    @php
        // Logika dipindahkan ke dalam loop, di mana $item sudah terdefinisi
        $isParent = !empty($item['children']);
        $padding = 'pl-' . (4 + ($level * 4)); // e.g., pl-4, pl-8, pl-12
    @endphp

    {{-- Baris untuk Akun Induk atau Akun Detail --}}
    <tr class="border-b border-gray-200">
        <td class="py-2 px-4 {{ $padding }}">
            <span class="{{ $isParent ? 'font-semibold text-gray-800' : 'text-gray-600' }}">
                {{ $item['account_name'] }}
            </span>
        </td>
        <td class="py-2 px-4 text-right {{ $isParent ? '' : 'text-gray-700' }}">
            {{-- Hanya tampilkan saldo untuk akun detail (tidak punya anak) --}}
            @if(!$isParent)
                {{ number_format($item['balance'], 0, ',', '.') }}
            @endif
        </td>
    </tr>

    {{-- Jika punya anak, panggil lagi partial ini dan tampilkan sub-totalnya --}}
    @if($isParent)
        @include('accounting::reports._report_hierarchy_row', ['items' => $item['children'], 'level' => $level + 1])
        <tr class="border-b-2 border-gray-300">
            <td class="py-2 px-4 font-bold text-gray-800 {{ $padding }}">
                Total {{ $item['account_name'] }}
            </td>
            <td class="py-2 px-4 text-right font-bold text-gray-800">
                {{ number_format($item['balance'], 0, ',', '.') }}
            </td>
        </tr>
    @endif
@endforeach
