@props(['action', 'class' => ''])

<form action="{{ $action }}" method="POST" class="inline">
    @csrf
    @method('DELETE')

    <button type="button" class="confirm-delete-button inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-md transition-colors duration-200 {{ $class }}">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Hapus
    </button>
</form>
