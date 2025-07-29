@php
    $controller = app('\Modules\MasterData\Http\Controllers\BrandController');
    $fields = $controller->getFormFields();
    $item = $item ?? null;
@endphp

<x-crud.form
    method="show"
    :item="$item"
    :fields="$fields"
    form-id="brand-show"
/>
