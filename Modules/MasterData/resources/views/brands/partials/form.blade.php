@php
    $controller = app('\Modules\MasterData\Http\Controllers\BrandController');
    $fields = $controller->getFormFields();
    $item = $item ?? null;
    $isModal = request()->ajax() || (isset($showButtons) && !$showButtons);
@endphp

<x-crud.form
    :method="$method ?? 'create'"
    :item="$item"
    :fields="$fields"
    form-id="brand-form"
    :show-buttons="!$isModal"
/>
