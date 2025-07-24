@props([
    'modalName',
    'modalTitle' => 'Formulir',
    'submitLabel' => 'Simpan',
    'cancelLabel' => 'Batal',
    'maxWidth' => 'lg',
    'formId' => 'generic-form',
    'formAction' => '',
    'submitHandler' => 'submitForm()',
])

<x-modal name="{{ $modalName }}" max-width="{{ $maxWidth }}">
    <form @submit.prevent="{{ $submitHandler }}" id="{{ $formId }}" :action="formAction">
        @csrf

        <h2 class="text-lg font-semibold text-gray-800 mb-4" x-text="modalTitle">{{ $modalTitle }}</h2>

        {{ $slot }}

        <div class="mt-6 flex justify-end gap-x-3">
            <button @click.prevent="$dispatch('close-modal')" type="button"
                class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                {{ $cancelLabel }}
            </button>
            <button type="submit"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</x-modal>
