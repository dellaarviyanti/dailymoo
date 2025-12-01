<div class="overflow-x-auto -mx-4 sm:mx-0">
    <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            No. Sapi
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Bobot (kg)
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Tanggal Pengukuran
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Catatan
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allWeights as $weight)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary/10 text-primary font-semibold text-xs sm:text-sm">
                                {{ $weight->cow_id }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <span class="text-xs sm:text-sm font-medium text-gray-900">
                                {{ number_format($weight->weight, 1) }} kg
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <span class="text-xs sm:text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($weight->measured_at)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="text-xs sm:text-sm text-gray-600 line-clamp-2">
                                {{ $weight->notes ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                            <div class="flex items-center gap-1 sm:gap-2">
                                <a href="{{ route('weight.edit', $weight) }}" 
                                   class="text-primary hover:text-primary-dark transition-colors">
                                    Edit
                                </a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('weight.destroy', $weight) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Hapus data bobot ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $allWeights->links() }}
</div>

