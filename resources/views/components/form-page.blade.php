@props(['title', 'description', 'formAction', 'model' => null])

<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="px-4 sm:px-0 mb-4">
        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $title }}</h3>
        <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
    </div>
    <form action="{{ $formAction }}" method="POST">
        @csrf
        @if($model)
            @method('PUT')
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                    {{ $fields }}
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
                <a href="{{ url()->previous() }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
