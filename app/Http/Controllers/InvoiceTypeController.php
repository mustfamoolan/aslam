<?php

namespace App\Http\Controllers;

use App\Models\InvoiceType;
use App\Models\DentalClinic;
use Illuminate\Http\Request;

class InvoiceTypeController extends Controller
{
    /**
     * تخزين نوع فاتورة جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['dental_clinic_id'] = DentalClinic::first()->id;

        $invoiceType = InvoiceType::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'invoiceType' => $invoiceType]);
        }

        return redirect()->back()
            ->with('success', 'تم إضافة نوع الفاتورة بنجاح');
    }

    /**
     * تحديث نوع فاتورة محدد
     */
    public function update(Request $request, InvoiceType $invoiceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $invoiceType->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'invoiceType' => $invoiceType]);
        }

        return redirect()->back()
            ->with('success', 'تم تحديث نوع الفاتورة بنجاح');
    }

    /**
     * حذف نوع فاتورة محدد
     */
    public function destroy(InvoiceType $invoiceType)
    {
        $invoiceType->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->with('success', 'تم حذف نوع الفاتورة بنجاح');
    }
}
