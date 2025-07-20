<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-4 border-b border-gray-200">
        <h3 class="font-bold text-lg text-gray-800">({{ $data['account']->account_code }}) {{ $data['account']->name }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Kredit</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Saldo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="font-semibold text-gray-700">
                    <td colspan="4" class="px-4 py-2">Saldo Awal</td>
                    <td class="px-4 py-2 text-right">{{ number_format($data['opening_balance'], 0, ',', '.') }}</td>
                </tr>

                @php $saldo = $data['opening_balance']; @endphp
                @foreach($data['transactions'] as $item)
                    @php $saldo += ($item->debit - $item->credit); @endphp
                    <tr>
                        <td class="px-4 py-2 text-gray-500">{{ $item->journalEntry->date->format('d-m-Y') }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item->journalEntry->description }}</td>
                        <td class="px-4 py-2 text-right text-green-600">{{ $item->debit > 0 ? number_format($item->debit, 0, ',', '.') : '' }}</td>
                        <td class="px-4 py-2 text-right text-red-600">{{ $item->credit > 0 ? number_format($item->credit, 0, ',', '.') : '' }}</td>
                        <td class="px-4 py-2 text-right text-gray-700">{{ number_format($saldo, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100">
                <tr class="font-bold text-gray-800">
                    <td colspan="4" class="px-4 py-3">Saldo Akhir</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
