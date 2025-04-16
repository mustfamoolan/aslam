@extends('layouts.patient')

@section('title', 'صور وأشعة المريض')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="patient-images-container">
    <!-- شريط التبويبات بالتصميم الجديد -->
    <div class="images-tabs-new">
        <button class="tab-btn-new active" data-tab="teeth-images">صور حالة الأسنان</button>
        <button class="tab-btn-new" data-tab="xray-images">صور الأشعة</button>
    </div>

    <!-- محتوى التبويبات -->
    <div class="tab-content">
        <!-- تبويب صور حالة الأسنان (الآن هو التبويب النشط) -->
        <div class="tab-pane active" id="teeth-images">
            <!-- حاوية أقسام قبل وبعد -->
            <div class="before-after-container">
                <!-- قسم صور ما قبل العلاج -->
                <div class="images-section">
                    <h3 class="section-title">قبل</h3>

                    <div class="images-grid">
                        @php
                            $beforeImages = $patient->beforeTeethImages()->latest()->get();
                            $placeholdersCount = max(0, 12 - $beforeImages->count());
                        @endphp

                        @if($beforeImages->count() > 0)
                            <div class="images-row">
                                @foreach($beforeImages as $index => $image)
                                    <div class="image-item">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="صورة قبل العلاج"
                                             onclick="openImageViewer('{{ asset('storage/' . $image->image_path) }}', '{{ $image->notes }}', '{{ $image->image_date->format('Y-m-d') }}')">
                                        <div class="image-actions">
                                            <span class="image-date">{{ $image->image_date->format('Y-m-d') }}</span>
                                            <button class="delete-image-btn" onclick="deleteImage('{{ route('patients.teeth-images.destroy', ['patient' => $patient->id, 'teethImage' => $image->id]) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if(($index + 1) % 3 == 0 && $index + 1 < $beforeImages->count())
                                        </div><div class="images-row">
                                    @endif
                                @endforeach

                                @for($i = 0; $i < $placeholdersCount; $i++)
                                    <div class="image-placeholder"></div>
                                    @if(($beforeImages->count() + $i + 1) % 3 == 0 && $i + 1 < $placeholdersCount)
                                        </div><div class="images-row">
                                    @endif
                                @endfor
                            </div>
                        @else
                            <!-- صفوف الصور - 4 أسطر -->
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                        @endif
                    </div>

                    <div class="add-image-button-container">
                        <a href="#" class="add-image-button-link" id="addBeforeImageBtn">
                            <i class="fas fa-plus"></i>
                            إضافة صورة جديدة
                        </a>
                    </div>
                </div>

                <!-- قسم صور ما بعد العلاج -->
                <div class="images-section">
                    <h3 class="section-title">بعد</h3>

                    <div class="images-grid">
                        @php
                            $afterImages = $patient->afterTeethImages()->latest()->get();
                            $placeholdersCount = max(0, 12 - $afterImages->count());
                        @endphp

                        @if($afterImages->count() > 0)
                            <div class="images-row">
                                @foreach($afterImages as $index => $image)
                                    <div class="image-item">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="صورة بعد العلاج"
                                             onclick="openImageViewer('{{ asset('storage/' . $image->image_path) }}', '{{ $image->notes }}', '{{ $image->image_date->format('Y-m-d') }}')">
                                        <div class="image-actions">
                                            <span class="image-date">{{ $image->image_date->format('Y-m-d') }}</span>
                                            <button class="delete-image-btn" onclick="deleteImage('{{ route('patients.teeth-images.destroy', ['patient' => $patient->id, 'teethImage' => $image->id]) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if(($index + 1) % 3 == 0 && $index + 1 < $afterImages->count())
                                        </div><div class="images-row">
                                    @endif
                                @endforeach

                                @for($i = 0; $i < $placeholdersCount; $i++)
                                    <div class="image-placeholder"></div>
                                    @if(($afterImages->count() + $i + 1) % 3 == 0 && $i + 1 < $placeholdersCount)
                                        </div><div class="images-row">
                                    @endif
                                @endfor
                            </div>
                        @else
                            <!-- صفوف الصور - 4 أسطر -->
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                            <div class="images-row">
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                                <div class="image-placeholder"></div>
                            </div>
                        @endif
                    </div>

                    <div class="add-image-button-container">
                        <a href="#" class="add-image-button-link" id="addAfterImageBtn">
                            <i class="fas fa-plus"></i>
                            إضافة صورة جديدة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- تبويب صور الأشعة -->
        <div class="tab-pane" id="xray-images">
            <!-- حقل البحث -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <input type="text" id="xraySearchInput" class="search-input" placeholder="ابحث عن عنوان أو تصنيف">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- تصنيف الأشعة -->
            <div class="filter-section">
                <h3 class="filter-title">تصنيف الأشعة</h3>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">الكل</button>
                    <button class="filter-btn" data-filter="starred">المميزة بنجمة</button>
                    <button class="filter-btn" data-filter="X">X</button>
                    <button class="filter-btn" data-filter="MRI">MRI</button>
                    <button class="filter-btn" data-filter="CT">CT</button>
                </div>
            </div>

            <!-- جدول الصور -->
            <div class="images-table-container">
                <table class="images-table">
                    <thead>
                        <tr>
                            <th>عنوان الأشعة</th>
                            <th>التصنيف</th>
                            <th>التاريــخ</th>
                            <th>منــذ</th>
                            <th>تمييز</th>
                            <th>عرض</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($patient->xrays->count() > 0)
                            @foreach($patient->xrays as $xray)
                            <tr class="xray-row" data-category="{{ $xray->category }}" data-starred="{{ $xray->is_starred ? 'true' : 'false' }}">
                                <td>
                                    <div class="image-title-cell">
                                        <i class="fas fa-image"></i>
                                        <span>{{ $xray->title }}</span>
                                    </div>
                                </td>
                                <td>{{ $xray->category }}</td>
                                <td>{{ $xray->xray_date->format('d / m / Y') }}</td>
                                <td>{{ $xray->time_since }}</td>
                                <td>
                                    <button class="star-btn" data-xray-id="{{ $xray->id }}" data-starred="{{ $xray->is_starred ? 'true' : 'false' }}">
                                        <i class="{{ $xray->is_starred ? 'fas' : 'far' }} fa-star"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="view-xray-btn" data-src="{{ asset('storage/' . $xray->image_path) }}" data-title="{{ $xray->title }}" data-notes="{{ $xray->notes }}" data-date="{{ $xray->xray_date->format('Y-m-d') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-xray-btn" data-xray-id="{{ $xray->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">لا توجد صور أشعة مسجلة لهذا المريض</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- زر إضافة صورة لأشعة جديدة -->
            <div class="add-image-button-container">
                <a href="#" class="add-image-button-link" id="addXrayImageBtn">
                    <i class="fas fa-plus"></i>
                    إضافة صورة أشعة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة صورة جديدة -->
<div id="addImageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>إضافة صورة جديدة</h3>
            <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addImageForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <h4 class="form-section-title">التفاصيل</h4>

                <input type="hidden" id="imageTypeValue" name="type" value="before">
                <input type="hidden" id="imageTypeField" value="قبل">

                <!-- حقل رفع الصورة -->
                <div class="file-upload-container">
                    <label for="image" class="file-upload-btn">
                        <i class="fas fa-plus"></i>
                        انقر لاختيار صورة
                    </label>
                    <input type="file" name="image" id="image" class="file-upload-input" accept="image/*" required>
                    <div class="file-name-display" id="fileName"></div>
                    <div class="image-preview-container">
                        <img id="imagePreview" src="#" alt="معاينة الصورة" style="display: none;">
                    </div>
                </div>

                <!-- حقول خاصة بالأشعة -->
                <div id="xrayFields" style="display: none;">
                    <div class="form-group">
                        <label for="title" class="form-label">عنوان الأشعة</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="أدخل عنوان الأشعة">
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">التصنيف</label>
                        <select name="category" id="category" class="form-control">
                            <option value="X">X</option>
                            <option value="MRI">MRI</option>
                            <option value="CT">CT</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_date" class="form-label">تاريخ الإضافة</label>
                    <input type="date" name="image_date" id="image_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">ملاحظة</label>
                    <textarea name="notes" id="notes" class="form-control" placeholder="اكتب ملاحظاتك"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">حفظ</button>
                    <button type="button" class="cancel-btn" onclick="document.getElementById('addImageModal').style.display='none'">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل التبويبات
        const tabButtons = document.querySelectorAll('.tab-btn-new');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // إزالة الفئة النشطة من جميع الأزرار والتبويبات
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // إضافة الفئة النشطة للزر والتبويب المحدد
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // تفعيل أزرار إضافة صورة
        const addImageButtons = document.querySelectorAll('#addBeforeImageBtn, #addAfterImageBtn, #addXrayImageBtn');
        addImageButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('تم الضغط على زر إضافة صورة');

                // تحديد نوع الصورة بناءً على الزر المضغوط
                let imageType = '';
                let imageTypeValue = '';

                if (this.id === 'addBeforeImageBtn') {
                    imageType = 'قبل';
                    imageTypeValue = 'before';
                    document.getElementById('xrayFields').style.display = 'none';
                } else if (this.id === 'addAfterImageBtn') {
                    imageType = 'بعد';
                    imageTypeValue = 'after';
                    document.getElementById('xrayFields').style.display = 'none';
                } else if (this.id === 'addXrayImageBtn') {
                    imageType = 'أشعة';
                    imageTypeValue = 'xray';
                    document.getElementById('xrayFields').style.display = 'block';
                }

                // تعيين نوع الصورة في المودال
                document.getElementById('imageTypeField').value = imageType;
                document.getElementById('imageTypeValue').value = imageTypeValue;

                // عرض المودال
                document.getElementById('addImageModal').style.display = 'flex';
            });
        });

        // إغلاق المودال عند النقر على زر الإغلاق
        document.querySelector('.modal-close').addEventListener('click', function() {
            document.getElementById('addImageModal').style.display = 'none';
        });

        // إغلاق المودال عند النقر خارج المحتوى
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('addImageModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // معاينة الصورة قبل الرفع
        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // عرض اسم الملف
                document.getElementById('fileName').textContent = file.name;

                // عرض معاينة الصورة
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById('fileName').textContent = 'لم يتم اختيار ملف';
                document.getElementById('imagePreview').style.display = 'none';
            }
        });

        // معالجة إرسال النموذج
        document.getElementById('addImageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('تم تقديم النموذج');

            const formData = new FormData(this);
            const imageType = document.getElementById('imageTypeValue').value;

            // طباعة محتويات النموذج للتصحيح
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            let url = '';

            // تحديد المسار المناسب للإرسال
            if (imageType === 'before' || imageType === 'after') {
                url = '{{ route("patients.teeth-images.store", ["patient" => $patient->id]) }}';
                console.log('نوع الصورة: صورة حالة الأسنان - ' + imageType);
            } else if (imageType === 'xray') {
                url = '{{ route("patients.xrays.store", ["patient" => $patient->id]) }}';
                console.log('نوع الصورة: صورة أشعة');
            }

            console.log('URL للإرسال: ' + url);

            // إظهار مؤشر التحميل
            const submitBtn = document.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'جاري الحفظ...';
            submitBtn.disabled = true;

            // استخدام fetch مع معالجة أفضل للأخطاء
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('استجابة الخادم:', response.status);

                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || `خطأ في الاستجابة: ${response.status}`);
                    });
                }

                return response.json();
            })
            .then(data => {
                console.log('بيانات الاستجابة:', data);

                if (data.status === 'success') {
                    // إغلاق المودال
                    document.getElementById('addImageModal').style.display = 'none';

                    // إعادة تحميل الصفحة لعرض الصورة الجديدة
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حفظ الصورة: ' + data.message);
                }
            })
            .catch(error => {
                console.error('خطأ:', error);
                alert('حدث خطأ أثناء حفظ الصورة: ' + error.message);
            })
            .finally(() => {
                // إعادة زر الإرسال إلى حالته الأصلية
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // تفعيل أزرار تمييز صور الأشعة بنجمة
        document.querySelectorAll('.star-btn').forEach(button => {
            button.addEventListener('click', function() {
                const xrayId = this.getAttribute('data-xray-id');
                const isStarred = this.getAttribute('data-starred') === 'true';
                const starIcon = this.querySelector('i');
                const row = this.closest('.xray-row');

                // بناء المسار بشكل صحيح
                const url = `/patients/{{ $patient->id }}/xrays/${xrayId}/toggle-star`;

                console.log('إرسال طلب تبديل النجمة إلى:', url);

                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            console.error('خطأ في الاستجابة:', response.status, data);
                            throw new Error(data.message || 'فشل في الاستجابة من الخادم');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('استجابة تبديل النجمة:', data);

                    if (data.status === 'success') {
                        // تحديث حالة النجمة
                        if (data.is_starred) {
                            starIcon.className = 'fas fa-star';
                            this.setAttribute('data-starred', 'true');
                            row.setAttribute('data-starred', 'true');
                        } else {
                            starIcon.className = 'far fa-star';
                            this.setAttribute('data-starred', 'false');
                            row.setAttribute('data-starred', 'false');
                        }
                    } else {
                        alert('حدث خطأ أثناء تحديث حالة التمييز: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('خطأ:', error);
                    alert('حدث خطأ أثناء تحديث حالة التمييز: ' + error.message);
                });
            });
        });

        // تفعيل أزرار عرض صور الأشعة
        const viewXrayButtons = document.querySelectorAll('.view-xray-btn');
        viewXrayButtons.forEach(button => {
            button.addEventListener('click', function() {
                const src = this.getAttribute('data-src');
                const title = this.getAttribute('data-title');
                const notes = this.getAttribute('data-notes');
                const date = this.getAttribute('data-date');

                openImageViewer(src, notes, date, title);
            });
        });

        // تفعيل أزرار حذف صور الأشعة
        const deleteXrayButtons = document.querySelectorAll('.delete-xray-btn');
        deleteXrayButtons.forEach(button => {
            button.addEventListener('click', function() {
                const xrayId = this.getAttribute('data-xray-id');

                if (confirm('هل أنت متأكد من رغبتك في حذف هذه الصورة؟')) {
                    fetch('{{ url("patients/'.$patient->id.'/xrays") }}/' + xrayId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('فشل في الاستجابة من الخادم');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // حذف الصف من الجدول
                            const row = this.closest('tr');
                            row.remove();

                            // التحقق مما إذا كان الجدول فارغًا
                            const tbody = document.querySelector('.images-table tbody');
                            if (tbody.children.length === 0) {
                                const emptyRow = document.createElement('tr');
                                emptyRow.innerHTML = '<td colspan="7" class="text-center">لا توجد صور أشعة مسجلة لهذا المريض</td>';
                                tbody.appendChild(emptyRow);
                            }
                        } else {
                            alert('حدث خطأ أثناء حذف الصورة: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('خطأ:', error);
                        alert('حدث خطأ أثناء حذف الصورة');
                    });
                }
            });
        });

        // تفعيل فلترة صور الأشعة
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // إزالة الفئة النشطة من جميع الأزرار
                filterButtons.forEach(btn => btn.classList.remove('active'));

                // إضافة الفئة النشطة للزر المحدد
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                const rows = document.querySelectorAll('.xray-row');

                rows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else if (filter === 'starred') {
                        row.style.display = row.getAttribute('data-starred') === 'true' ? '' : 'none';
                    } else {
                        row.style.display = row.getAttribute('data-category') === filter ? '' : 'none';
                    }
                });
            });
        });

        // تفعيل البحث في صور الأشعة
        const searchInput = document.getElementById('xraySearchInput');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.xray-row');

            rows.forEach(row => {
                const title = row.querySelector('.image-title-cell span').textContent.toLowerCase();
                const category = row.getAttribute('data-category').toLowerCase();

                if (title.includes(searchTerm) || category.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // دالة لفتح عارض الصور
    function openImageViewer(imageSrc, notes, date, title = '') {
        // إنشاء عنصر عارض الصور إذا لم يكن موجودًا
        let viewer = document.getElementById('imageViewer');
        if (!viewer) {
            viewer = document.createElement('div');
            viewer.id = 'imageViewer';
            viewer.className = 'image-viewer';
            viewer.innerHTML = `
                <div class="image-viewer-content">
                    <span class="close-viewer">&times;</span>
                    <div class="image-title-display"></div>
                    <img id="viewerImage" src="" alt="صورة مكبرة">
                    <div class="image-info">
                        <div class="image-date-display"></div>
                        <div class="image-notes-display"></div>
                    </div>
                </div>
            `;
            document.body.appendChild(viewer);

            // إضافة مستمع حدث لزر الإغلاق
            viewer.querySelector('.close-viewer').addEventListener('click', function() {
                viewer.style.display = 'none';
            });

            // إغلاق العارض عند النقر خارج الصورة
            viewer.addEventListener('click', function(e) {
                if (e.target === viewer) {
                    viewer.style.display = 'none';
                }
            });
        }

        // تعيين مصدر الصورة والمعلومات
        viewer.querySelector('#viewerImage').src = imageSrc;

        // عرض العنوان إذا كان موجودًا (لصور الأشعة)
        const titleDisplay = viewer.querySelector('.image-title-display');
        if (title) {
            titleDisplay.textContent = title;
            titleDisplay.style.display = 'block';
        } else {
            titleDisplay.style.display = 'none';
        }

        viewer.querySelector('.image-date-display').textContent = 'التاريخ: ' + date;
        viewer.querySelector('.image-notes-display').textContent = notes ? 'ملاحظات: ' + notes : '';

        // عرض العارض
        viewer.style.display = 'flex';
    }

    // دالة لحذف الصورة
    function deleteImage(url) {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذه الصورة؟')) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('فشل في الاستجابة من الخادم');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // إعادة تحميل الصفحة لتحديث العرض
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف الصورة: ' + data.message);
                }
            })
            .catch(error => {
                console.error('خطأ:', error);
                alert('حدث خطأ أثناء حذف الصورة');
            });
        }
    }
</script>
@endsection

@section('styles')
<style>
    .patient-images-container {
        padding: 20px;
        background-color: #f5f7fa;
        min-height: 80vh;
    }

    /* تصميم شريط التبويبات المتصلة */
    .images-tabs-new {
        display: flex;
        margin-bottom: 30px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .tab-btn-new {
        flex: 1;
        padding: 15px;
        text-align: center;
        background-color: white;
        border: none;
        font-size: 18px;
        font-weight: 600;
        color: #22577A;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .tab-btn-new.active {
        background-color: #22577A;
        color: white;
    }

    /* تصميم محتوى التبويبات */
    .tab-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .tab-pane {
        display: none;
        padding: 20px;
    }

    .tab-pane.active {
        display: block;
    }

    /* تصميم خاص لتبويب صور حالة الأسنان */
    #teeth-images {
        background-color: transparent;
        box-shadow: none;
    }

    /* تصميم خاص لتبويب صور الأشعة */
    #xray-images {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* تصميم حاوية قبل وبعد */
    .before-after-container {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
    }

    .before-after-container .images-section {
        flex: 1;
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* تصميم شبكة الصور */
    .images-grid {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .images-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .image-item {
        width: 130px;
        height: 130px;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .image-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .image-placeholder {
        width: 130px;
        height: 130px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px dashed #ccc;
        transition: all 0.3s ease;
    }

    .image-placeholder:hover {
        border-color: #22577A;
        box-shadow: 0 0 0 2px rgba(60, 169, 158, 0.1);
    }

    /* تصميم زر إضافة صورة */
    .add-image-button-container {
        margin-top: 30px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px dashed #e0e0e0;
    }

    .add-image-button-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #22577A;
        text-decoration: none;
        padding: 10px 20px;
        border: 1px dashed #22577A;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .add-image-button-link:hover {
        background-color: #f0f9f8;
    }

    /* تصميم حقل البحث الجديد مع أيقونة البحث في اليسار */
    .search-container {
        margin: 20px 0;
        display: flex;
        justify-content: center;
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .search-input {
        width: 100%;
        padding: 15px 20px 15px 60px; /* تعديل التباعد ليناسب موضع الزر الجديد */
        border: none;
        font-size: 16px;
        color: #333;
        outline: none;
    }

    .search-btn {
        position: absolute;
        left: 0; /* تغيير من right إلى left */
        top: 0;
        height: 100%;
        width: 50px;
        background-color: #22A7A2;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        background-color: #1C8A86;
    }

    /* تصميم قسم التصفية */
    .filter-section {
        margin: 25px 0;
    }

    .filter-title {
        color: #1e5a7e;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 20px;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        background-color: white;
        color: #555;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background-color: #1e5a7e;
        color: white;
        border-color: #1e5a7e;
    }

    /* تصميم جدول الصور */
    .images-table-container {
        margin-top: 20px;
        overflow-x: auto;
    }

    .images-table {
        width: 100%;
        border-collapse: collapse;
    }

    .images-table th {
        padding: 15px 10px;
        text-align: right;
        border-bottom: 2px solid #e0e0e0;
        color: #1e5a7e;
        font-weight: 600;
        font-size: 14px;
    }

    .images-table td {
        padding: 15px 10px;
        border-bottom: 1px solid #e0e0e0;
        color: #333;
        font-size: 14px;
    }

    .image-title-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-title-cell i {
        color: #22577A;
        font-size: 16px;
    }

    .star-btn {
        background: none;
        border: none;
        color: #f1c40f;
        cursor: pointer;
        font-size: 16px;
    }

    .star-btn:hover {
        color: #f39c12;
    }

    /* تصميم المودال الجديد */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #f5f7fa;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        position: relative;
    }

    .modal-header {
        background-color: white;
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #eaeaea;
        position: relative;
    }

    .modal-header h3 {
        margin: 0;
        color: #22577A;
        font-size: 22px;
        font-weight: 600;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 24px;
        color: #aaa;
        cursor: pointer;
        transition: color 0.3s;
    }

    .modal-close:hover {
        color: #555;
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        color: #22577A;
        font-weight: 600;
        font-size: 16px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
        background-color: white;
    }

    .form-control:focus {
        border-color: #22577A;
        outline: none;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .submit-btn {
        padding: 12px 30px;
        background-color: #22577A;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #1a4257;
    }

    .cancel-btn {
        padding: 12px 30px;
        background-color: #f1f1f1;
        color: #555;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .cancel-btn:hover {
        background-color: #e5e5e5;
    }

    .form-section-title {
        color: #0088cc;
        font-size: 18px;
        margin-bottom: 20px;
        text-align: center;
    }

    /* تصميم حقل التاريخ */
    input[type="date"] {
        direction: rtl;
    }

    /* تصميم حقل الملاحظات */
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    /* تصميم حقل رفع الصورة */
    .file-upload-container {
        position: relative;
        margin-bottom: 20px;
        text-align: center;
    }

    .file-upload-btn {
        display: inline-block;
        padding: 15px 30px;
        background-color: #f5f5f5;
        border: 2px dashed #ddd;
        border-radius: 10px;
        color: #777;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s;
    }

    .file-upload-btn:hover {
        background-color: #eaeaea;
        border-color: #ccc;
    }

    .file-upload-btn i {
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
    }

    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-name-display {
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }

    /* تصميم عارض الصور */
    .image-viewer {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }

    .image-viewer-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .close-viewer {
        position: absolute;
        top: 10px;
        right: 15px;
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        z-index: 2001;
        text-shadow: 0 0 3px black;
    }

    #viewerImage {
        max-width: 100%;
        max-height: 80vh;
        display: block;
    }

    .image-info {
        padding: 15px;
        background-color: white;
    }

    .image-date-display {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .image-notes-display {
        color: #555;
    }

    /* تصميم أزرار عرض وحذف صور الأشعة */
    .view-xray-btn, .delete-xray-btn, .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        transition: all 0.3s ease;
    }

    .view-xray-btn {
        color: #22577A;
    }

    .view-xray-btn:hover {
        color: #38A3A5;
    }

    .delete-xray-btn {
        color: #ff5252;
    }

    .delete-xray-btn:hover {
        color: #ff1a1a;
    }

    .star-btn {
        color: #ffc107;
    }

    .star-btn:hover {
        color: #ffdb58;
    }

    /* تصميم جدول الأشعة */
    .images-table-container {
        margin-top: 20px;
        overflow-x: auto;
    }

    .images-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .images-table th, .images-table td {
        padding: 12px 15px;
        text-align: right;
    }

    .images-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    .images-table tr {
        border-bottom: 1px solid #e9ecef;
    }

    .images-table tr:last-child {
        border-bottom: none;
    }

    .images-table tr:hover {
        background-color: #f8f9fa;
    }

    .image-title-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-title-cell i {
        color: #22577A;
    }

    /* تصميم أزرار التصفية */
    .filter-section {
        margin-top: 20px;
    }

    .filter-title {
        font-size: 16px;
        margin-bottom: 10px;
        color: #333;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 15px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active, .filter-btn:hover {
        background-color: #22577A;
        color: white;
        border-color: #22577A;
    }

    /* تصميم عنوان الصورة في العارض */
    .image-title-display {
        padding: 10px 15px;
        background-color: #22577A;
        color: white;
        font-weight: bold;
        font-size: 18px;
        text-align: center;
    }

    /* تصميم معاينة الصورة */
    .image-preview-container {
        margin-top: 15px;
        text-align: center;
    }

    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
