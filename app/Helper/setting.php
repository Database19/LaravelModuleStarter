<?php

use Illuminate\Support\Facades\Request;

function getModuleName(): string
{
    return ucfirst(Request::segment(1) ?? 'Unknown');
}

function format_currency($value): string
    {
        $amount = (float) $value;
        return 'Rp ' . number_format($amount, 2, ',', '.');
    }
