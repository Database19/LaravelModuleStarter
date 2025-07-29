<?php

namespace Modules\QualityControl\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\QualityCheck;
use App\Models\QualityStandard;
use App\Models\Product;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QualityCheckController extends Controller
{
    public function index()
    {
        $qualityChecks = QualityCheck::with(['product', 'inspector', 'qualityStandard'])
            ->orderBy('inspection_date', 'desc')
            ->paginate(15);

        return view('qualitycontrol::checks.index', compact('qualityChecks'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $inspectors = User::where('is_super_admin', true)
                         ->orWhere('name', 'like', '%quality%')
                         ->orWhere('name', 'like', '%inspector%')
                         ->get();
        $qualityStandards = QualityStandard::where('is_active', true)->orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'received')->orderBy('order_number')->get();
        $salesOrders = SalesOrder::where('status', 'confirmed')->orderBy('order_number')->get();

        return view('qualitycontrol::checks.create', compact(
            'products', 'inspectors', 'qualityStandards', 'purchaseOrders', 'salesOrders'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:incoming,in_process,final,customer_return',
            'product_id' => 'required|exists:products,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'inspector_id' => 'required|exists:users,id',
            'quality_standard_id' => 'required|exists:quality_standards,id',
            'sample_size' => 'required|integer|min:1',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $qualityCheck = QualityCheck::create([
                'check_number' => QualityCheck::generateCheckNumber(),
                'type' => $validated['type'],
                'product_id' => $validated['product_id'],
                'purchase_order_id' => $validated['purchase_order_id'] ?? null,
                'sales_order_id' => $validated['sales_order_id'] ?? null,
                'inspector_id' => $validated['inspector_id'],
                'quality_standard_id' => $validated['quality_standard_id'],
                'sample_size' => $validated['sample_size'],
                'defect_count' => 0,
                'defect_rate' => 0,
                'result' => 'pass', // Default, will be updated during inspection
                'status' => 'pending',
                'inspection_date' => $validated['inspection_date'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create inspection items based on quality standard
            $this->createInspectionItems($qualityCheck);
        });

        alert()->success('Berhasil!', 'Quality check berhasil dibuat.');
        return redirect()->route('qualitycontrol.checks.index');
    }

    public function show(QualityCheck $qualityCheck)
    {
        $qualityCheck->load([
            'product',
            'inspector',
            'qualityStandard',
            'inspections',
            'purchaseOrder',
            'salesOrder',
            'createdBy',
            'updatedBy'
        ]);

        return view('qualitycontrol::checks.show', compact('qualityCheck'));
    }

    public function edit(QualityCheck $qualityCheck)
    {
        if ($qualityCheck->status === 'completed') {
            alert()->error('Error!', 'Cannot edit completed quality check.');
            return redirect()->back();
        }

        $products = Product::where('is_active', true)->orderBy('name')->get();
        $inspectors = User::where('is_super_admin', true)
                         ->orWhere('name', 'like', '%quality%')
                         ->orWhere('name', 'like', '%inspector%')
                         ->get();
        $qualityStandards = QualityStandard::where('is_active', true)->orderBy('name')->get();

        $qualityCheck->load(['inspections']);

        return view('qualitycontrol::checks.edit', compact(
            'qualityCheck', 'products', 'inspectors', 'qualityStandards'
        ));
    }

    public function update(Request $request, QualityCheck $qualityCheck)
    {
        if ($qualityCheck->status === 'completed') {
            alert()->error('Error!', 'Cannot update completed quality check.');
            return redirect()->back();
        }

        $validated = $request->validate([
            'inspector_id' => 'required|exists:users,id',
            'sample_size' => 'required|integer|min:1',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,rejected',
            'inspections' => 'required|array',
            'inspections.*.actual_value' => 'required',
            'inspections.*.result' => 'required|in:pass,fail,na',
            'inspections.*.remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $qualityCheck) {
            // Update quality check
            $qualityCheck->update([
                'inspector_id' => $validated['inspector_id'],
                'sample_size' => $validated['sample_size'],
                'inspection_date' => $validated['inspection_date'],
                'notes' => $validated['notes'],
                'status' => $validated['status'],
                'updated_by' => Auth::id(),
            ]);

            // Update inspection items
            foreach ($validated['inspections'] as $inspectionId => $inspectionData) {
                $qualityCheck->inspections()->where('id', $inspectionId)->update([
                    'actual_value' => $inspectionData['actual_value'],
                    'result' => $inspectionData['result'],
                    'remarks' => $inspectionData['remarks'] ?? null,
                ]);
            }

            // Calculate overall result
            $this->calculateOverallResult($qualityCheck);
        });

        alert()->success('Berhasil!', 'Quality check berhasil diupdate.');
        return redirect()->route('qualitycontrol.checks.show', $qualityCheck);
    }

    public function destroy(QualityCheck $qualityCheck)
    {
        if ($qualityCheck->status === 'completed') {
            alert()->error('Error!', 'Cannot delete completed quality check.');
            return redirect()->back();
        }

        $qualityCheck->delete();
        alert()->success('Berhasil!', 'Quality check berhasil dihapus.');
        return redirect()->route('qualitycontrol.checks.index');
    }

    private function createInspectionItems(QualityCheck $qualityCheck)
    {
        $standard = $qualityCheck->qualityStandard;
        $parameters = $standard->parameters;

        foreach ($parameters as $parameter) {
            $qualityCheck->inspections()->create([
                'parameter_name' => $parameter['name'],
                'parameter_type' => $parameter['type'],
                'expected_value' => $parameter['expected_value'],
                'actual_value' => '',
                'unit' => $parameter['unit'] ?? null,
                'tolerance_min' => $parameter['tolerance_min'] ?? null,
                'tolerance_max' => $parameter['tolerance_max'] ?? null,
                'is_critical' => $parameter['is_critical'] ?? false,
                'result' => 'na',
            ]);
        }
    }

    private function calculateOverallResult(QualityCheck $qualityCheck)
    {
        $inspections = $qualityCheck->inspections;
        $failedInspections = $inspections->where('result', 'fail');
        $criticalFailures = $failedInspections->where('is_critical', true);

        if ($criticalFailures->count() > 0) {
            $result = 'fail';
        } elseif ($failedInspections->count() > 0) {
            $result = 'conditional';
        } else {
            $result = 'pass';
        }

        $defectCount = $failedInspections->count();
        $defectRate = ($defectCount / $inspections->count()) * 100;

        $qualityCheck->update([
            'defect_count' => $defectCount,
            'defect_rate' => $defectRate,
            'result' => $result,
        ]);
    }
}
