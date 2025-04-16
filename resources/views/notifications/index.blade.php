@extends('layouts.app')

@section('title', 'التنبيهات - نظام إدارة عيادات الأسنان')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">التنبيهات</h5>
                    <div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                            <i class="fas fa-plus"></i> إضافة تنبيه
                        </button>
                        <button class="btn btn-sm btn-secondary" id="mark-all-read-btn">
                            <i class="fas fa-check-double"></i> تحديد الكل كمقروء
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="notifications-list">
                            @foreach($notifications as $notification)
                                <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" data-id="{{ $notification->id }}">
                                    <div class="notification-icon {{ $notification->type }}">
                                        <i class="fas fa-{{ $notification->type === 'info' ? 'info-circle' : ($notification->type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle') }}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-title">{{ $notification->title }}</div>
                                        <div class="notification-text">{{ $notification->content }}</div>
                                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="notification-actions">
                                        @if(!$notification->is_read)
                                            <button class="btn btn-sm btn-link mark-read-btn" data-id="{{ $notification->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-link text-danger delete-notification-btn" data-id="{{ $notification->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center p-5">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <h5>لا توجد تنبيهات</h5>
                            <p class="text-muted">ستظهر هنا التنبيهات الهامة للعيادة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة تنبيه -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNotificationModalLabel">إضافة تنبيه جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNotificationForm" action="{{ route('notifications.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان التنبيه</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">محتوى التنبيه</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">نوع التنبيه</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="info">معلومات</option>
                            <option value="warning">تحذير</option>
                            <option value="danger">خطر</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">إضافة</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تحديد تنبيه كمقروء
        $('.mark-read-btn').on('click', function() {
            const id = $(this).data('id');
            const button = $(this);

            $.ajax({
                url: `/notifications/${id}/mark-as-read`,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    // تحديث واجهة المستخدم
                    button.closest('.notification-item').removeClass('unread').addClass('read');
                    button.remove();

                    // عرض رسالة نجاح
                    toastr.success('تم تحديد التنبيه كمقروء');
                }
            });
        });

        // تحديد جميع التنبيهات كمقروءة
        $('#mark-all-read-btn').on('click', function() {
            $.ajax({
                url: "{{ route('notifications.mark-all-as-read') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    // تحديث واجهة المستخدم
                    $('.notification-item').removeClass('unread').addClass('read');
                    $('.mark-read-btn').remove();

                    // عرض رسالة نجاح
                    toastr.success('تم تحديد جميع التنبيهات كمقروءة');
                }
            });
        });

        // حذف تنبيه
        $('.delete-notification-btn').on('click', function() {
            const id = $(this).data('id');
            const button = $(this);

            if (confirm('هل أنت متأكد من حذف هذا التنبيه؟')) {
                $.ajax({
                    url: `/notifications/${id}`,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // تحديث واجهة المستخدم
                        button.closest('.notification-item').fadeOut(300, function() {
                            $(this).remove();

                            // التحقق مما إذا كانت القائمة فارغة
                            if ($('.notification-item').length === 0) {
                                $('.notifications-list').html(`
                                    <div class="text-center p-5">
                                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                                        <h5>لا توجد تنبيهات</h5>
                                        <p class="text-muted">ستظهر هنا التنبيهات الهامة للعيادة</p>
                                    </div>
                                `);
                            }
                        });

                        // عرض رسالة نجاح
                        toastr.success('تم حذف التنبيه بنجاح');
                    }
                });
            }
        });

        // إضافة تنبيه جديد
        $('#addNotificationForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.text();

            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // إغلاق المودال
                    $('#addNotificationModal').modal('hide');

                    // إعادة تحميل الصفحة
                    location.reload();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(errors[field][0]);
                        }
                    } else {
                        toastr.error('حدث خطأ أثناء إضافة التنبيه');
                    }
                },
                complete: function() {
                    // إعادة زر الإرسال إلى حالته الأصلية
                    submitBtn.html(originalBtnText).prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
