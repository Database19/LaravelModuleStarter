@props([
    'method' => 'create',
    'item' => null,
    'fields' => [],
    'formId' => 'crud-form',
    'showButtons' => true
])

@php
    $isEdit = $method === 'edit';
    $isView = $method === 'show';
    $isCreate = $method === 'create';
@endphp

<div class="bg-white overflow-hidden">
    <!-- Form Body -->
    <form id="{{ $formId }}" class="p-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($fields as $field)
                @php
                    $fieldType = $field['type'] ?? 'text';
                    $fieldName = $field['name'];
                    $fieldLabel = $field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName));
                    $fieldValue = $isEdit || $isView ? ($item->{$fieldName} ?? '') : ($field['default'] ?? '');
                    $fieldRequired = $field['required'] ?? false;
                    $fieldOptions = $field['options'] ?? [];
                    $fieldAttributes = $field['attributes'] ?? [];
                    $fieldPlaceholder = $field['placeholder'] ?? '';
                    $fieldHelp = $field['help'] ?? '';
                    $colSpan = $field['col_span'] ?? 1;
                @endphp

                <div class="{{ $colSpan == 2 ? 'lg:col-span-2' : '' }}">
                    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $fieldLabel }}
                        @if($fieldRequired && !$isView)
                            <span class="text-red-500 ml-1">*</span>
                        @endif
                    </label>

                    @if($fieldType === 'select')
                        <select
                            name="{{ $fieldName }}"
                            id="{{ $fieldName }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error($fieldName) border-red-500 ring-2 ring-red-200 @enderror"
                            @if($fieldRequired && !$isView) required @endif
                            @if($isView) disabled @endif
                            @foreach($fieldAttributes as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
                        >
                            <option value="">Choose {{ $fieldLabel }}</option>
                            @foreach($fieldOptions as $value => $label)
                                <option value="{{ $value }}" {{ $fieldValue == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                    @elseif($fieldType === 'textarea')
                        <textarea
                            name="{{ $fieldName }}"
                            id="{{ $fieldName }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error($fieldName) border-red-500 ring-2 ring-red-200 @enderror"
                            placeholder="{{ $fieldPlaceholder }}"
                            rows="{{ $field['rows'] ?? 3 }}"
                            @if($fieldRequired && !$isView) required @endif
                            @if($isView) readonly @endif
                            @foreach($fieldAttributes as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
                        >{{ $fieldValue }}</textarea>

                    @elseif($fieldType === 'checkbox')
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                name="{{ $fieldName }}"
                                id="{{ $fieldName }}"
                                value="1"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ $fieldValue ? 'checked' : '' }}
                                @if($isView) disabled @endif
                                @foreach($fieldAttributes as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
                            />
                            <label for="{{ $fieldName }}" class="ml-2 block text-sm text-gray-900">
                                {{ $fieldHelp ?: "Enable {$fieldLabel}" }}
                            </label>
                        </div>

                    @else
                        <input
                            type="{{ $fieldType }}"
                            name="{{ $fieldName }}"
                            id="{{ $fieldName }}"
                            value="{{ $fieldValue }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error($fieldName) border-red-500 ring-2 ring-red-200 @enderror"
                            placeholder="{{ $fieldPlaceholder }}"
                            @if($fieldRequired && !$isView) required @endif
                            @if($isView) readonly @endif
                            @foreach($fieldAttributes as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach
                        />
                    @endif

                    @if($fieldHelp && $fieldType !== 'checkbox')
                        <p class="mt-1 text-sm text-gray-500">{{ $fieldHelp }}</p>
                    @endif

                    @error($fieldName)
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endforeach
        </div>
    </form>
</div>
