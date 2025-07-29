<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function executive()
    {
        return view('reports.executive');
    }

    public function sales()
    {
        return view('reports.sales');
    }

    public function financial()
    {
        return redirect()->route('accounting.reports.index');
    }

    public function inventory()
    {
        return redirect()->route('inventory.products.index');
    }

    public function hr()
    {
        return redirect()->route('humanresource.employees.index');
    }

    public function custom()
    {
        return view('reports.custom');
    }
}
