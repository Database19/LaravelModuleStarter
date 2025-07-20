@props(['title', 'description', 'addRoute'])

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    {{-- Header Halaman --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ $title }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ $description }}</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ $addRoute }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Baru
            </a>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="mt-8 flex flex-col">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            {{ $thead }}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        {{ $tbody }}
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $pagination }}
            </div>
        </div>
    </div>
</div>
