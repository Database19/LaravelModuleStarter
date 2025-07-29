<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Models\Bom;
use App\Models\ManufacturingOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
// use Modules\Manufacturing\Events\ManufacturingOrderCompleted; // Pastikan event ini ada

class ManufacturingOrderController extends Controller
{
    /**
     * Menampilkan daftar semua Manufacturing Orders.
     */
    public function index()
    {
        $manufacturingOrders = ManufacturingOrder::with('product')->latest()->paginate(15);
        return view('manufacturing::manufacturing-orders.index', compact('manufacturingOrders'));
    }

    /**
     * Menampilkan form untuk membuat Manufacturing Order baru.
     */
    public function create()
    {
        $boms = Bom::with('finishedGood')->get();
        return view('manufacturing::manufacturing-orders.create', compact('boms'));
    }

    /**
     * Menyimpan Manufacturing Order baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bom_id' => 'required|exists:boms,id',
            'quantity_to_produce' => 'required|numeric|min:1',
            'start_date' => 'required|date',
        ]);

        $bom = Bom::find($validated['bom_id']);

        ManufacturingOrder::create([
            'mo_number' => 'MO-' . date('Ym') .'-'. str_pad(ManufacturingOrder::count() + 1, 4, '0', STR_PAD_LEFT),
            'product_id' => $bom->product_id,
            'bom_id' => $validated['bom_id'],
            'quantity_to_produce' => $validated['quantity_to_produce'],
            'start_date' => $validated['start_date'],
            'status' => 'Pending', // Status awal
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        alert()->success('Berhasil!', 'Manufacturing Order baru telah dibuat.');
        return redirect()->route('manufacturing.manufacturing-orders.index');
    }

    /**
     * Menampilkan detail satu Manufacturing Order.
     */
    public function show(ManufacturingOrder $manufacturingOrder)
    {
        $manufacturingOrder->load('product', 'bom.items.component');
        return view('manufacturing::manufacturing-orders.show', compact('manufacturingOrder'));
    }

    /**
     * Menampilkan form untuk mengedit Manufacturing Order.
     */
    public function edit(ManufacturingOrder $manufacturingOrder)
    {
        // Hanya MO dengan status 'Pending' yang boleh diedit
        if ($manufacturingOrder->status !== 'Pending') {
            alert()->error('Gagal!', 'Hanya MO dengan status "Pending" yang bisa diedit.');
            return redirect()->route('manufacturing.manufacturing-orders.show', $manufacturingOrder);
        }

        $boms = Bom::with('finishedGood')->get();
        return view('manufacturing::manufacturing-orders.edit', compact('manufacturingOrder', 'boms'));
    }

    /**
     * Memperbarui Manufacturing Order di database.
     */
    public function update(Request $request, ManufacturingOrder $manufacturingOrder)
    {
        $validated = $request->validate([
            'bom_id' => 'required|exists:boms,id',
            'quantity_to_produce' => 'required|numeric|min:1',
            'start_date' => 'required|date',
        ]);

        $bom = Bom::find($validated['bom_id']);

        $manufacturingOrder->update([
            'product_id' => $bom->product_id,
            'bom_id' => $validated['bom_id'],
            'quantity_to_produce' => $validated['quantity_to_produce'],
            'start_date' => $validated['start_date'],
            'updated_by' => auth()->id(),
        ]);

        alert()->success('Berhasil!', 'Manufacturing Order telah diperbarui.');
        return redirect()->route('manufacturing.manufacturing-orders.index');
    }

    /**
     * Menghapus Manufacturing Order.
     */
    public function destroy(ManufacturingOrder $manufacturingOrder)
    {
        // Hanya MO dengan status 'Pending' yang boleh dihapus
        if ($manufacturingOrder->status !== 'Pending') {
            alert()->error('Gagal!', 'Hanya MO dengan status "Pending" yang bisa dihapus.');
            return back();
        }

        $manufacturingOrder->delete();
        alert()->success('Berhasil!', 'Manufacturing Order telah dihapus.');
        return redirect()->route('manufacturing.manufacturing-orders.index');
    }
}
