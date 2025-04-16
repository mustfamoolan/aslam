@extends('layouts.statistics_layout')

@section('styles')
<style>
    /* تنسيق جدول المعلومات */
    .appointments-summary {
        margin: 30px 0;
        display: flex;
        justify-content: flex-start;
    }

    .appointments-table {
        width: 350px;
        border-collapse: collapse;
        border-spacing: 0;
        margin-right: 0;
        margin-left: auto;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .appointments-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .appointments-table tr:last-child {
        border-bottom: none;
    }

    .appointments-table td {
        padding: 15px;
        background-color: white;
    }

    .appointment-label {
        color: #0277BD;
        font-weight: 500;
        font-size: 16px;
        text-align: right;
        border-left: 1px solid #f0f0f0;
        letter-spacing: 0.5px;
        padding-right: 20px;
    }

    .appointment-value {
        font-size: 22px;
        font-weight: bold;
        text-align: center;
        color: #22577A;
        width: 80px;
    }

    /* تنسيق الرسم البياني */
    .chart-container {
        height: 350px;
        margin: 30px auto;
        max-width: 350px;
    }

    /* تنسيق وسيلة الإيضاح */
    .chart-legend {
        display: flex;
        justify-content: space-around;
        margin-top: 30px;
        padding: 20px;
        flex-direction: column;
        align-items: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        width: 100%;
        max-width: 350px;
        justify-content: space-between;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-left: 10px;
    }

    .completed-color {
        background-color: #5ECBC7;
    }

    .pending-color {
        background-color: #F9D923;
    }

    .cancelled-color {
        background-color: #FF6B6B;
    }

    .legend-text {
        font-size: 16px;
        margin-left: auto;
        color: #22577A;
        flex-grow: 1;
    }

    .legend-percentage {
        font-weight: bold;
        font-size: 24px;
        margin-right: 10px;
        color: #22577A;
    }

    /* تنسيق زر اختيار التاريخ */
    .year-selector {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 20px;
        padding: 8px 15px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .year-selector i {
        margin-right: 10px;
        color: #22577A;
    }

    #selectedDate {
        font-weight: bold;
        color: #22577A;
    }

    /* تنسيق مربع حوار اختيار التاريخ */
    .date-selector-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        width: 500px;
        z-index: 1050;
        overflow: hidden;
    }

    .date-selector-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .date-selector-header h3 {
        margin: 0;
        font-size: 18px;
        color: #22577A;
    }

    .close-btn {
        background: none;
        border: none;
        color: #999;
        font-size: 18px;
        cursor: pointer;
    }

    .date-selector-content {
        display: flex;
        padding: 20px;
    }

    .date-column {
        flex: 1;
        padding: 0 10px;
    }

    .date-column h4 {
        margin-bottom: 15px;
        color: #22577A;
        text-align: center;
    }

    .year-options, .month-options {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .year-option, .month-option {
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .year-option:hover, .month-option:hover {
        background-color: #f5f5f5;
    }

    .year-option.active, .month-option.active {
        background-color: #e9f7ff;
        color: #22577A;
        font-weight: bold;
    }

    .date-selector-footer {
        padding: 15px 20px;
        text-align: center;
        border-top: 1px solid #eee;
    }

    .apply-date-btn {
        background-color: #22577A;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        cursor: pointer;
        font-size: 16px;
    }
</style>
@endsection

@section('title', 'إحصائيات المواعيد')

@section('content')
<!-- كارد إحصائيات المواعيد -->
<div class="stats-card">
    <div class="stats-header">
        <h2 class="stats-title">إحصائيات المواعيد خلال التاريخ المُحدد :</h2>
        <div class="year-selector">
            <span id="selectedDate">{{ date('Y') }} / {{ date('m') }}</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>

    <div class="appointments-summary">
        <table class="appointments-table">
            <tr>
                <td class="appointment-label">المواعيد الكُـليـة</td>
                <td class="appointment-value" id="totalAppointments">22</td>
            </tr>
            <tr>
                <td class="appointment-label">المواعيد المكتملة</td>
                <td class="appointment-value" id="completedAppointments">16</td>
            </tr>
            <tr>
                <td class="appointment-label">المواعيد الملغية</td>
                <td class="appointment-value" id="cancelledAppointments">3</td>
            </tr>
            <tr>
                <td class="appointment-label">المواعيد القادمـة</td>
                <td class="appointment-value" id="pendingAppointments">3</td>
            </tr>
        </table>
    </div>

    <div class="chart-container">
        <canvas id="appointmentsChart"></canvas>
    </div>

    <div class="chart-legend">
        <div class="legend-item">
            <span class="legend-color cancelled-color"></span>
            <span class="legend-text">المواعيد الملغية</span>
            <span class="legend-percentage" id="cancelledPercentage">30%</span>
        </div>
        <div class="legend-item">
            <span class="legend-color pending-color"></span>
            <span class="legend-text">المواعيد القادمة</span>
            <span class="legend-percentage" id="pendingPercentage">10%</span>
        </div>
        <div class="legend-item">
            <span class="legend-color completed-color"></span>
            <span class="legend-text">المواعيد المكتملة</span>
            <span class="legend-percentage" id="completedPercentage">60%</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let appointmentsChart;
        let currentYear = {{ date('Y') }};
        let currentMonth = {{ date('m') }};

        // تحميل إحصائيات المواعيد
        loadAppointmentStats(currentYear, currentMonth);

        // تفعيل اختيار التاريخ
        $('.year-selector').click(function() {
            showDateSelector();
        });

        // تحميل إحصائيات المواعيد
        function loadAppointmentStats(year, month) {
            $.ajax({
                url: "{{ route('statistics.appointments') }}",
                method: "GET",
                data: {
                    year: year,
                    month: month
                },
                success: function(data) {
                    // تحديث البيانات في الصفحة
                    $('#selectedDate').text(year + ' / ' + (month < 10 ? '0' + month : month));

                    // تحديث إحصائيات المواعيد
                    const total = data.statusStats.completed + data.statusStats.pending + data.statusStats.cancelled;
                    $('#totalAppointments').text(total);
                    $('#completedAppointments').text(data.statusStats.completed);
                    $('#cancelledAppointments').text(data.statusStats.cancelled);
                    $('#pendingAppointments').text(data.statusStats.pending);

                    // حساب النسب المئوية
                    const completedPercentage = Math.round((data.statusStats.completed / total) * 100) || 0;
                    const pendingPercentage = Math.round((data.statusStats.pending / total) * 100) || 0;
                    const cancelledPercentage = Math.round((data.statusStats.cancelled / total) * 100) || 0;

                    $('#completedPercentage').text(completedPercentage + '%');
                    $('#pendingPercentage').text(pendingPercentage + '%');
                    $('#cancelledPercentage').text(cancelledPercentage + '%');

                    // إنشاء الرسم البياني
                    createAppointmentsChart(data.statusStats);
                },
                error: function(xhr) {
                    console.error("حدث خطأ أثناء تحميل البيانات:", xhr);
                }
            });
        }

        // إنشاء الرسم البياني للمواعيد
        function createAppointmentsChart(statusStats) {
            // إذا كان الرسم البياني موجودًا بالفعل، قم بتدميره
            if (appointmentsChart) {
                appointmentsChart.destroy();
            }

            const ctx = document.getElementById('appointmentsChart').getContext('2d');
            appointmentsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['المواعيد المكتملة', 'المواعيد القادمة', 'المواعيد الملغية'],
                    datasets: [{
                        data: [statusStats.completed, statusStats.pending, statusStats.cancelled],
                        backgroundColor: ['#5ECBC7', '#F9D923', '#FF6B6B'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#22577A',
                            padding: 15,
                            cornerRadius: 10,
                            displayColors: false,
                            titleFont: {
                                size: 16
                            },
                            bodyFont: {
                                size: 14
                            },
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = Math.round((value / total) * 100) || 0;
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // عرض مربع حوار اختيار التاريخ
        function showDateSelector() {
            // إنشاء مربع حوار اختيار التاريخ
            const years = [];
            const currentFullYear = new Date().getFullYear();

            // إنشاء قائمة بالسنوات (5 سنوات سابقة و5 سنوات قادمة)
            for (let i = currentFullYear - 5; i <= currentFullYear + 5; i++) {
                years.push(i);
            }

            // إنشاء قائمة بالشهور
            const months = [
                { value: 1, name: 'يناير' },
                { value: 2, name: 'فبراير' },
                { value: 3, name: 'مارس' },
                { value: 4, name: 'أبريل' },
                { value: 5, name: 'مايو' },
                { value: 6, name: 'يونيو' },
                { value: 7, name: 'يوليو' },
                { value: 8, name: 'أغسطس' },
                { value: 9, name: 'سبتمبر' },
                { value: 10, name: 'أكتوبر' },
                { value: 11, name: 'نوفمبر' },
                { value: 12, name: 'ديسمبر' }
            ];

            // إنشاء HTML لمربع الحوار
            let dialogHTML = `
                <div class="date-selector-dialog">
                    <div class="date-selector-header">
                        <h3>اختر التاريخ</h3>
                        <button class="close-btn">&times;</button>
                    </div>
                    <div class="date-selector-content">
                        <div class="date-column">
                            <h4>السنة</h4>
                            <div class="year-options">
            `;

            // إضافة خيارات السنوات
            years.forEach(year => {
                const isActive = year === currentYear ? 'active' : '';
                dialogHTML += `<div class="year-option ${isActive}" data-year="${year}">${year}</div>`;
            });

            dialogHTML += `
                            </div>
                        </div>
                        <div class="date-column">
                            <h4>الشهر</h4>
                            <div class="month-options">
            `;

            // إضافة خيارات الشهور
            months.forEach(month => {
                const isActive = month.value === currentMonth ? 'active' : '';
                dialogHTML += `<div class="month-option ${isActive}" data-month="${month.value}">${month.name}</div>`;
            });

            dialogHTML += `
                            </div>
                        </div>
                    </div>
                    <div class="date-selector-footer">
                        <button class="apply-date-btn">تطبيق</button>
                    </div>
                </div>
            `;

            // إضافة مربع الحوار إلى الصفحة
            $('body').append(dialogHTML);

            // تفعيل زر الإغلاق
            $('.close-btn').click(function() {
                $('.date-selector-dialog').remove();
            });

            // تفعيل اختيار السنة
            $('.year-option').click(function() {
                $('.year-option').removeClass('active');
                $(this).addClass('active');
            });

            // تفعيل اختيار الشهر
            $('.month-option').click(function() {
                $('.month-option').removeClass('active');
                $(this).addClass('active');
            });

            // تفعيل زر التطبيق
            $('.apply-date-btn').click(function() {
                const selectedYear = $('.year-option.active').data('year');
                const selectedMonth = $('.month-option.active').data('month');

                // تحميل البيانات للتاريخ المحدد
                loadAppointmentStats(selectedYear, selectedMonth);

                // إغلاق مربع الحوار
                $('.date-selector-dialog').remove();
            });
        }
    });
</script>
@endsection
