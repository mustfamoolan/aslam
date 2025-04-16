@extends('layouts.inventory_layout')

@section('title', 'نظام المخزون')

@section('content')
<!-- مربع البحث -->
<div class="search-container">
    <div class="search-bar">
        <input type="text" id="item-search" placeholder="البحث عن عنصر محدد">
        <button type="submit" id="search-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<!-- حاوية المحتوى الرئيسي (الكارد + الجدول) -->
<div class="main-inventory-container">
    <!-- كارد حالة المخزون -->
    <div class="inventory-status-container">
        <div class="inventory-status-card">
            <div class="donut-chart-container">
                <div class="donut-chart">
                    <!-- الرسم البياني الدائري سيتم إنشاؤه بواسطة CSS -->
                    <div class="donut-chart-inner"></div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color sufficient"></span>
                        <span class="legend-text">المواد الكافية</span>
                        <span class="legend-value">{{ $stats['sufficient'] }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color low"></span>
                        <span class="legend-text">المواد الناقصة</span>
                        <span class="legend-value">{{ $stats['low'] }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color warning"></span>
                        <span class="legend-text">المواد المحذورة</span>
                        <span class="legend-value">{{ $stats['damaged'] }}</span>
                    </div>
                </div>
            </div>
            <div class="inventory-status-text">
                <h3>حالة المخزون</h3>
                <h2>
                    @if($stats['damaged'] > 0)
                        تحتاج للمراجعة!
                    @elseif($stats['low'] > 0)
                        بحاجة للتجديد
                    @else
                        ممتازة!
                    @endif
                </h2>
            </div>
            <div class="add-material-btn-container">
                <button class="add-material-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fas fa-plus-circle"></i>
                    إضافة مادة
                </button>
            </div>
        </div>
    </div>

    <!-- قسم جدول المخزون -->
    <div class="inventory-container">
        <div class="inventory-wrapper">
            <!-- جدول المخزون -->
            <div class="inventory-table-container">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ت</th>
                            <th>اسم المادة</th>
                            <th>العدد</th>
                            <th>الحالة</th>
                            <th>تاريخ انتهاء الصلاحية</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryItems as $index => $item)
                        <tr>
                            <td class="id-cell">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="name-cell">{{ $item->name }}</td>
                            <td class="quantity-cell">{{ $item->quantity }}</td>
                            <td class="status-cell">
                                <span class="status-badge status-{{ $item->status }}">{{ $item->status_name }}</span>
                            </td>
                            <td class="expiry-cell">{{ $item->expiry_date ? $item->expiry_date->format('d/m/Y') : '-' }}</td>
                            <td class="actions-cell">
                                <button class="action-btn delete-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="action-btn edit-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">لا توجد عناصر في المخزون حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة عنصر جديد -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="add-item-title">إضافة مادة جديدة</h2>

                <form id="addItemForm">
                    @csrf
                    <div class="form-group">
                        <label for="item_name">اسم المادة</label>
                        <input type="text" class="form-control" id="item_name" name="name" placeholder="أكتب اسم المادة المراد إضافتها" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">العـــــدد</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="أكتب العدد المتوفر" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="expiry_date">تاريخ انتهاء الصلاحية</label>
                        <div class="date-select-wrapper">
                            <input type="date" class="form-control date-select" id="expiry_date" name="expiry_date">
                            <div class="date-select-placeholder">
                                <i class="fas fa-chevron-down"></i>
                                <span>اضغط لاختياره</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-add-item">إضافة المادة</button>
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودال تعديل عنصر -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="add-item-title">تعديل مادة</h2>

                <form id="editItemForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_item_id" name="item_id">

                    <div class="form-group">
                        <label for="edit_item_name">اسم المادة</label>
                        <input type="text" class="form-control" id="edit_item_name" name="name" placeholder="أكتب اسم المادة" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_quantity">العـــــدد</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" placeholder="أكتب العدد المتوفر" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_expiry_date">تاريخ انتهاء الصلاحية</label>
                        <div class="date-select-wrapper">
                            <input type="date" class="form-control date-select" id="edit_expiry_date" name="expiry_date">
                            <div class="date-select-placeholder">
                                <i class="fas fa-chevron-down"></i>
                                <span>اضغط لاختياره</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-add-item">حفظ التغييرات</button>
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* تنسيق حاوية المحتوى الرئيسي */
    .main-inventory-container {
        display: flex;
        flex-direction: row-reverse; /* لجعل الكارد على اليمين */
        gap: 20px;
        margin: 20px;
        align-items: flex-start;
    }

    /* تنسيق كارد حالة المخزون */
    .inventory-status-container {
        display: flex;
        justify-content: center;
        width: 300px; /* عرض ثابت للكارد */
        flex-shrink: 0; /* منع تقليص الكارد */
    }

    .inventory-status-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        width: 100%;
        height: 1100px; /* زيادة طول الكارد إلى 1200 بكسل */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* تعديل توزيع العناصر داخل الكارد */
    .donut-chart-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin-top: 80px; /* زيادة الهامش العلوي */
        margin-bottom: 80px; /* زيادة الهامش السفلي */
    }

    .inventory-status-text {
        text-align: center;
        margin: 100px 0; /* زيادة الهوامش */
    }

    .add-material-btn-container {
        width: 100%;
        margin-top: auto; /* دفع الزر إلى أسفل الكارد */
        margin-bottom: 80px; /* زيادة الهامش السفلي */
        border-top: 1px dashed #e0e0e0;
        padding-top: 20px;
    }

    /* باقي التنسيقات بدون تغيير */
    .donut-chart {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: conic-gradient(
            #FF5252 0deg 30deg,
            #FFD740 30deg 40deg,
            #5ECBC7 40deg 360deg
        );
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .donut-chart-inner {
        width: 70%;
        height: 70%;
        background-color: white;
        border-radius: 50%;
    }

    .chart-legend {
        margin-top: 15px;
        width: 100%;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        justify-content: space-between;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-left: 8px;
    }

    .legend-color.sufficient {
        background-color: #5ECBC7;
    }

    .legend-color.low {
        background-color: #FFD740;
    }

    .legend-color.warning {
        background-color: #FF5252;
    }

    .legend-text {
        flex-grow: 1;
        font-size: 14px;
        color: #555;
    }

    .legend-value {
        font-weight: bold;
        color: #333;
    }

    .inventory-status-text h3 {
        color: #5ECBC7;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .inventory-status-text h2 {
        color: #5ECBC7;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .add-material-btn {
        width: 100%;
        background-color: transparent;
        border: 1px dashed #5ECBC7;
        color: #5ECBC7;
        border-radius: 8px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-material-btn i {
        margin-left: 8px;
    }

    .add-material-btn:hover {
        background-color: rgba(94, 203, 199, 0.1);
    }

    /* تنسيق جدول المخزون */
    .inventory-container {
        flex-grow: 1; /* السماح للجدول بأخذ المساحة المتبقية */
        min-width: 0; /* للسماح بالتقليص إذا لزم الأمر */
    }

    .inventory-wrapper {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 1100px;
        display: flex;
        flex-direction: column;
    }

    .inventory-table-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    .inventory-table thead {
        background-color: transparent;
    }

    .inventory-table th {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 10;
        padding: 15px 10px;
        text-align: right;
        font-weight: bold;
        color: #5ECBC7;
        border-bottom: 1px solid #e0e0e0;
        font-size: 16px;
    }

    .inventory-table td {
        padding: 15px 10px;
        text-align: right;
        border-bottom: 1px solid #f0f0f0;
        color: #555;
        font-size: 14px;
    }

    .inventory-table tr:hover {
        background-color: #f9f9f9;
    }

    /* تنسيق خلايا الجدول */
    .id-cell {
        color: #aaa;
        font-size: 12px;
        width: 40px;
    }

    .name-cell {
        font-weight: bold;
        color: #333;
    }

    .quantity-cell {
        font-weight: bold;
        color: #333;
        text-align: center;
    }

    .status-cell {
        text-align: center;
    }

    .expiry-cell {
        color: #5ECBC7;
        font-weight: 500;
    }

    /* تنسيق شارات الحالة */
    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        min-width: 80px;
        text-align: center;
    }

    .status-sufficient {
        background-color: #5ECBC7;
        color: white;
    }

    .status-low {
        background-color: #FFD740;
        color: #333;
    }

    .status-warning, .status-damaged {
        background-color: #FF5252;
        color: white;
    }

    /* تنسيق أزرار الإجراءات */
    .actions-cell {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .edit-btn {
        color: #5ECBC7;
    }

    .delete-btn {
        color: #FF5252;
    }

    .action-btn:hover {
        background-color: #f0f0f0;
    }

    /* تنسيق للشاشات الصغيرة */
    @media (max-width: 992px) {
        .main-inventory-container {
            flex-direction: column; /* تغيير الاتجاه للشاشات الصغيرة */
            align-items: center;
        }

        .inventory-status-container {
            width: 100%;
            max-width: 300px;
            margin-bottom: 20px;
        }

        .inventory-container {
            width: 100%;
        }
    }

    /* تنسيق مودال إضافة مادة جديدة */
    .modal-content {
        border-radius: 15px;
        border: none;
        background-color: #f8fafc;
        overflow: hidden;
    }

    .modal-body {
        padding: 30px;
    }

    .add-item-title {
        color: #22577A;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        color: #22577A;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: none;
        background-color: #e9f0f7;
        color: #333;
        font-size: 14px;
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    .date-select-wrapper {
        position: relative;
    }

    .date-select {
        padding-right: 40px;
    }

    .date-select-placeholder {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding-right: 15px;
        color: #a0aec0;
        pointer-events: none;
    }

    .date-select-placeholder i {
        margin-left: 8px;
    }

    /* إخفاء placeholder عند تحديد تاريخ */
    .date-select:not(:placeholder-shown) + .date-select-placeholder {
        display: none;
    }

    .modal-footer {
        border-top: none;
        padding: 20px 0 0;
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .btn-add-item {
        background-color: #22577A;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-add-item:hover {
        background-color: #1a4d6c;
    }

    .btn-cancel {
        background-color: white;
        color: #666;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background-color: #f5f5f5;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تفعيل البحث عند الضغط على زر البحث
        $('#search-btn').on('click', function(e) {
            e.preventDefault();
            const searchTerm = $('#item-search').val();
            console.log('البحث عن:', searchTerm);
            // يمكن إضافة كود للبحث هنا
        });

        // تفعيل حقل التاريخ
        $('#expiry_date').on('change', function() {
            if ($(this).val()) {
                $('.date-select-placeholder').hide();
            } else {
                $('.date-select-placeholder').show();
            }
        });

        // تقديم نموذج إضافة مادة
        $('#addItemForm').on('submit', function(e) {
            e.preventDefault();

            // جمع بيانات النموذج
            const formData = {
                name: $('#item_name').val(),
                quantity: $('#quantity').val(),
                expiry_date: $('#expiry_date').val()
            };

            // إرسال البيانات إلى الخادم
            $.ajax({
                url: "{{ route('inventory.store') }}",
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // إغلاق المودال
                        $('#addItemModal').modal('hide');

                        // إعادة تحميل الصفحة لعرض العنصر الجديد
                        location.reload();
                    }
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            alert(errors[key][0]);
                        }
                    } else {
                        alert('حدث خطأ أثناء إضافة العنصر');
                    }
                }
            });
        });

        // حذف عنصر
        $('.delete-btn').on('click', function() {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                const itemId = $(this).data('id');

                $.ajax({
                    url: `/inventory/${itemId}`,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // إعادة تحميل الصفحة
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                });
            }
        });

        // تفعيل زر التعديل
        $('.edit-btn').on('click', function() {
            const itemId = $(this).data('id');
            const row = $(this).closest('tr');

            // استخراج بيانات العنصر من الصف
            const name = row.find('.name-cell').text();
            const quantity = row.find('.quantity-cell').text();

            const expiryDateText = row.find('.expiry-cell').text();
            let expiryDate = '';

            // تحويل تاريخ الانتهاء من dd/mm/yyyy إلى yyyy-mm-dd (تنسيق HTML5 date input)
            if (expiryDateText && expiryDateText !== '-') {
                const parts = expiryDateText.split('/');
                expiryDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
            }

            // تعبئة نموذج التعديل بالبيانات الحالية
            $('#edit_item_id').val(itemId);
            $('#edit_item_name').val(name);
            $('#edit_quantity').val(quantity);
            $('#edit_expiry_date').val(expiryDate);

            // إظهار/إخفاء placeholder تاريخ الانتهاء
            if (expiryDate) {
                $('#editItemModal .date-select-placeholder').hide();
            } else {
                $('#editItemModal .date-select-placeholder').show();
            }

            // عرض مودال التعديل
            $('#editItemModal').modal('show');
        });

        // تفعيل حقل التاريخ في مودال التعديل
        $('#edit_expiry_date').on('change', function() {
            if ($(this).val()) {
                $('#editItemModal .date-select-placeholder').hide();
            } else {
                $('#editItemModal .date-select-placeholder').show();
            }
        });

        // تقديم نموذج تعديل العنصر
        $('#editItemForm').on('submit', function(e) {
            e.preventDefault();

            const itemId = $('#edit_item_id').val();

            // جمع بيانات النموذج
            const formData = {
                name: $('#edit_item_name').val(),
                quantity: $('#edit_quantity').val(),
                expiry_date: $('#edit_expiry_date').val()
            };

            // إرسال البيانات إلى الخادم
            $.ajax({
                url: `/inventory/${itemId}`,
                method: "PUT",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // إغلاق المودال
                        $('#editItemModal').modal('hide');

                        // إعادة تحميل الصفحة لعرض التغييرات
                        location.reload();
                    }
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            alert(errors[key][0]);
                        }
                    } else {
                        alert('حدث خطأ أثناء تحديث العنصر');
                    }
                }
            });
        });
    });
</script>
@endsection
