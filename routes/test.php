<?php

use Illuminate\Support\Facades\Route;

// Test route for modal form
Route::get('/test-form', function () {
    $fields = [
        [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'required' => true,
            'placeholder' => 'Enter brand name'
        ],
        [
            'name' => 'logo_url',
            'label' => 'Logo URL',
            'type' => 'url',
            'required' => false,
            'placeholder' => 'https://example.com/logo.png'
        ],
        [
            'name' => 'is_active',
            'label' => 'Active Status',
            'type' => 'checkbox',
            'required' => false,
            'default' => true,
            'help' => 'Check to activate this brand'
        ]
    ];

    if (request()->ajax()) {
        return view('components.crud.form', [
            'method' => 'create',
            'item' => null,
            'fields' => $fields,
            'formId' => 'test-form'
        ])->render();
    }

    return view('components.crud.form', [
        'method' => 'create',
        'item' => null,
        'fields' => $fields,
        'formId' => 'test-form'
    ]);
})->name('test.form');
