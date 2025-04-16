<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\InvoiceType;
use App\Models\DentalClinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * عرض قائمة الفواتير
     */
    public function index()
    {
        $invoices = Invoice::with('patient')->latest()->paginate(10);
        $patients = Patient::all();
        $invoiceTypes = InvoiceType::where('dental_clinic_id', DentalClinic::first()->id)->get();

        return view('invoices.index', compact('invoices', 'patients', 'invoiceTypes'));
    }

    /**
     * تخزين فاتورة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_type' => 'required|string',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'session_title' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // حساب المبلغ المتبقي
        $remaining_amount = $validated['amount'] - $validated['paid_amount'];

        // تحديد ما إذا كانت الفاتورة مدفوعة بالكامل
        $is_paid = ($remaining_amount <= 0);

        // إضافة معرف العيادة والمبلغ المتبقي وحالة الدفع
        $validated['dental_clinic_id'] = DentalClinic::first()->id;
        $validated['remaining_amount'] = $remaining_amount;
        $validated['is_paid'] = $is_paid;

        $invoice = Invoice::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'invoice' => $invoice]);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'تم إضافة الفاتورة بنجاح');
    }

    /**
     * عرض فاتورة محددة
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * عرض نموذج تعديل فاتورة
     */
    public function edit(Invoice $invoice)
    {
        $patients = Patient::all();
        $invoiceTypes = InvoiceType::where('dental_clinic_id', DentalClinic::first()->id)->get();

        return view('invoices.edit', compact('invoice', 'patients', 'invoiceTypes'));
    }

    /**
     * تحديث فاتورة محددة
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_type' => 'required|string',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'session_title' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // حساب المبلغ المتبقي
        $remaining_amount = $validated['amount'] - $validated['paid_amount'];

        // تحديد ما إذا كانت الفاتورة مدفوعة بالكامل
        $is_paid = ($remaining_amount <= 0);

        // إضافة المبلغ المتبقي وحالة الدفع
        $validated['remaining_amount'] = $remaining_amount;
        $validated['is_paid'] = $is_paid;

        $invoice->update($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    /**
     * حذف فاتورة محددة
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }
}
