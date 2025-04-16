<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمخزون
     */
    public function index()
    {
        // الحصول على عيادة المستخدم الحالي
        $clinicId = auth()->user()->dental_clinic_id;

        // تحديث حالات جميع العناصر تلقائيًا
        $this->updateAllItemsStatus($clinicId);

        // الحصول على عناصر المخزون
        $inventoryItems = InventoryItem::where('dental_clinic_id', $clinicId)
            ->orderBy('created_at', 'desc')
            ->get();

        // إحصائيات المخزون
        $stats = [
            'sufficient' => $inventoryItems->where('status', 'sufficient')->count(),
            'damaged' => $inventoryItems->where('status', 'damaged')->count(),
            'low' => $inventoryItems->where('status', 'low')->count(),
            'total' => $inventoryItems->count()
        ];

        return view('inventory.index', compact('inventoryItems', 'stats'));
    }

    /**
     * تحديث حالات جميع العناصر تلقائيًا
     */
    private function updateAllItemsStatus($clinicId)
    {
        $items = InventoryItem::where('dental_clinic_id', $clinicId)->get();

        foreach ($items as $item) {
            $newStatus = $this->determineItemStatus(
                $item->quantity,
                $item->expiry_date
            );

            // تحديث الحالة فقط إذا كانت مختلفة
            if ($item->status !== $newStatus) {
                $item->status = $newStatus;
                $item->save();
            }
        }
    }

    /**
     * تحديد حالة العنصر تلقائيًا
     */
    private function determineItemStatus($quantity, $expiryDate)
    {
        // إذا انتهت صلاحية المادة، تكون الحالة "تالف"
        if ($expiryDate && now()->gt($expiryDate)) {
            return 'damaged';
        }

        // إذا كانت الكمية صفر، تكون الحالة "ناقص"
        if ($quantity <= 0) {
            return 'low';
        }

        // إذا كانت الكمية أقل من النصف (نفترض أن النصف هو 10)، تكون الحالة "ناقص"
        if ($quantity < 10) {
            return 'low';
        }

        // غير ذلك، تكون الحالة "كافٍ"
        return 'sufficient';
    }

    /**
     * إضافة عنصر جديد للمخزون
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        // تحديد حالة العنصر تلقائيًا
        $status = $this->determineItemStatus(
            $validated['quantity'],
            $validated['expiry_date'] ? \Carbon\Carbon::parse($validated['expiry_date']) : null
        );

        // إضافة عنصر جديد
        $item = new InventoryItem();
        $item->dental_clinic_id = auth()->user()->dental_clinic_id;
        $item->name = $validated['name'];
        $item->quantity = $validated['quantity'];
        $item->status = $status;
        $item->expiry_date = $validated['expiry_date'];
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة العنصر بنجاح',
            'item' => $item
        ]);
    }

    /**
     * حذف عنصر من المخزون
     */
    public function destroy(InventoryItem $item)
    {
        // التحقق من أن العنصر ينتمي لعيادة المستخدم الحالي
        if ($item->dental_clinic_id != auth()->user()->dental_clinic_id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا العنصر'
            ], 403);
        }

        // حذف العنصر
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العنصر بنجاح'
        ]);
    }

    /**
     * عرض صفحة فئات المخزون
     */
    public function categories()
    {
        return view('inventory.categories');
    }

    /**
     * عرض صفحة الموردين
     */
    public function suppliers()
    {
        return view('inventory.suppliers');
    }

    /**
     * عرض صفحة التقارير
     */
    public function reports()
    {
        return view('inventory.reports');
    }

    /**
     * تحديث عنصر في المخزون
     */
    public function update(Request $request, InventoryItem $item)
    {
        // التحقق من أن العنصر ينتمي لعيادة المستخدم الحالي
        if ($item->dental_clinic_id != auth()->user()->dental_clinic_id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا العنصر'
            ], 403);
        }

        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        // تحديد حالة العنصر تلقائيًا
        $status = $this->determineItemStatus(
            $validated['quantity'],
            $validated['expiry_date'] ? \Carbon\Carbon::parse($validated['expiry_date']) : null
        );

        // تحديث العنصر
        $item->name = $validated['name'];
        $item->quantity = $validated['quantity'];
        $item->status = $status; // تعيين الحالة تلقائيًا
        $item->expiry_date = $validated['expiry_date'];
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العنصر بنجاح',
            'item' => $item
        ]);
    }
}
