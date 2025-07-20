<?php

namespace Modules\Inventory\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Routing\Controller;

class StockMovementController extends Controller
{
    /**
     * Menampilkan riwayat pergerakan stok.
     */
    public function index()
    {
        $movements = StockMovement::with(['product', 'warehouse'])
            ->latest()
            ->paginate(20);

        return view('inventory::stock-movements.index', compact('movements'));
    }
}
