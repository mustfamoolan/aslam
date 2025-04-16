@extends('layouts.statistics_layout')

@section('title', 'إحصائيات المرضى')

@section('content')
<!-- كارد إحصائيات المرضى -->
<div class="stats-card">
    <div class="stats-header">
        <h2 class="stats-title">إحصائيات المرضى خلال السنة الحالية :</h2>
        <div class="year-selector">
            <span id="selectedYear">{{ date('Y') }}</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>

    <div class="stats-summary">
        <div class="stats-box">
            <div class="stats-box-title">العدد الكلي للمرضى :</div>
            <div class="stats-box-value" id="totalPatients">-</div>
            <div class="stats-box-subtitle">المرضى الجــــدد : <span id="newPatients">-</span></div>
        </div>

        <div class="stats-edit-btn">
            <i class="fas fa-pen"></i>
        </div>

        <div class="stats-range">
            <div class="range-item">
                <div class="range-label">الحد الأعلى</div>
                <div class="range-value" id="maxValue">200</div>
            </div>
            <div class="range-item">
                <div class="range-label">الحد الأدنى</div>
                <div class="range-value" id="minValue">0</div>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="patientsChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let patientsChart;
        let maxChartValue = 200;
        let currentYear = {{ date('Y') }};

        // تحميل إحصائيات المرضى
        loadPatientStats(currentYear);

        // تفعيل اختيار السنة
        $('.year-selector').click(function() {
            showYearSelector();
        });

        // تفعيل زر التعديل
        $('.stats-edit-btn').click(function() {
            showChartSettings();
        });

        // تحميل إحصائيات المرضى
        function loadPatientStats(year) {
            $.ajax({
                url: "{{ route('statistics.patients') }}",
                method: "GET",
                data: { year: year },
                success: function(data) {
                    // تحديث البيانات في الصفحة
                    $('#totalPatients').text(data.totalPatients);
                    $('#newPatients').text(data.newPatients);
                    $('#selectedYear').text(data.year);

                    // إنشاء الرسم البياني
                    createPatientsChart(data.monthlyStats);
                },
                error: function(xhr) {
                    console.error("حدث خطأ أثناء تحميل البيانات:", xhr);
                }
            });
        }

        // إنشاء الرسم البياني للمرضى
        function createPatientsChart(monthlyData) {
            const months = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

            // إذا كان الرسم البياني موجودًا بالفعل، قم بتدميره
            if (patientsChart) {
                patientsChart.destroy();
            }

            const ctx = document.getElementById('patientsChart').getContext('2d');
            patientsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'عدد المرضى',
                        data: monthlyData,
                        backgroundColor: '#22577A',
                        borderRadius: 10,
                        maxBarThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxChartValue,
                            grid: {
                                color: '#f0f0f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
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
                                title: function(tooltipItems) {
                                    return 'شهر ' + tooltipItems[0].label;
                                },
                                label: function(context) {
                                    return 'عدد المرضى: ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        }

        // عرض مربع حوار اختيار السنة
        function showYearSelector() {
            // إنشاء قائمة بالسنوات (5 سنوات سابقة و5 سنوات قادمة)
            let years = [];
            const currentYearJS = new Date().getFullYear();

            for (let i = currentYearJS - 5; i <= currentYearJS + 5; i++) {
                years.push(i);
            }

            // إنشاء HTML للقائمة المنسدلة
            let yearOptions = '';
            years.forEach(year => {
                yearOptions += `<div class="year-option ${year === currentYear ? 'active' : ''}" data-year="${year}">${year}</div>`;
            });

            // إنشاء مربع الحوار
            const yearSelectorDialog = `
                <div class="year-selector-dialog">
                    <div class="year-selector-header">
                        <h3>اختر السنة</h3>
                        <button class="close-btn"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="year-options">
                        ${yearOptions}
                    </div>
                </div>
            `;

            // إضافة مربع الحوار إلى الصفحة
            $('body').append(yearSelectorDialog);

            // تفعيل زر الإغلاق
            $('.close-btn').click(function() {
                $('.year-selector-dialog').remove();
            });

            // تفعيل اختيار السنة
            $('.year-option').click(function() {
                const selectedYear = $(this).data('year');
                currentYear = selectedYear;
                loadPatientStats(selectedYear);
                $('.year-selector-dialog').remove();
            });
        }

        // عرض مربع حوار إعدادات الرسم البياني
        function showChartSettings() {
            // إنشاء مربع الحوار
            const chartSettingsDialog = `
                <div class="chart-settings-dialog">
                    <div class="chart-settings-header">
                        <h3>إعدادات الرسم البياني</h3>
                        <button class="close-btn"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="chart-settings-content">
                        <div class="form-group">
                            <label>الحد الأعلى للرسم البياني:</label>
                            <input type="number" id="maxChartValueInput" class="form-control" value="${maxChartValue}" min="10" step="10">
                        </div>
                        <button class="btn btn-primary save-settings-btn">حفظ الإعدادات</button>
                    </div>
                </div>
            `;

            // إضافة مربع الحوار إلى الصفحة
            $('body').append(chartSettingsDialog);

            // تفعيل زر الإغلاق
            $('.close-btn').click(function() {
                $('.chart-settings-dialog').remove();
            });

            // تفعيل زر الحفظ
            $('.save-settings-btn').click(function() {
                const newMaxValue = parseInt($('#maxChartValueInput').val());

                if (newMaxValue >= 10) {
                    maxChartValue = newMaxValue;
                    $('#maxValue').text(maxChartValue);

                    // إعادة تحميل الرسم البياني بالقيمة الجديدة
                    loadPatientStats(currentYear);

                    $('.chart-settings-dialog').remove();
                }
            });
        }
    });
</script>

<style>
    /* تنسيق مربع حوار اختيار السنة */
    .year-selector-dialog, .chart-settings-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        width: 300px;
        z-index: 1050;
        overflow: hidden;
    }

    .year-selector-header, .chart-settings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .year-selector-header h3, .chart-settings-header h3 {
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

    .year-options {
        max-height: 300px;
        overflow-y: auto;
    }

    .year-option {
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .year-option:hover {
        background-color: #f5f5f5;
    }

    .year-option.active {
        background-color: #e9f7ff;
        color: #22577A;
        font-weight: bold;
    }

    /* تنسيق مربع حوار إعدادات الرسم البياني */
    .chart-settings-content {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #22577A;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .save-settings-btn {
        background-color: #22577A;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        width: 100%;
        cursor: pointer;
    }
</style>
@endsection
