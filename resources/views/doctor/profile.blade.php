@extends('layouts.app')

@section('title', 'معلومات الطبيب والعيادة')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="text-primary text-center mb-5">معلومات الطبيب</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('doctor.update-profile') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="name" class="form-label fw-bold">الاسم</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="name" name="name" value="{{ $doctor->name }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="specialty" class="form-label fw-bold">الاختصاص</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="specialty" name="specialty" value="{{ $doctor->specialty ? $doctor->specialty->name : 'جراحة وزراعة الأسنان' }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="phone" class="form-label fw-bold">رقم الهاتف</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="phone" name="phone" value="{{ $doctor->phone }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="code" class="form-label fw-bold">الرمز</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="code" name="code" value="ABC26" readonly>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary px-5">حفظ التغييرات</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-5 ms-2">الغاء</a>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="profile-image-container">
                                <img src="{{ asset('images/11.png') }}" alt="صورة الطبيب" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;">
                                <div class="mt-3">
                                    <a href="#" class="btn btn-sm btn-outline-primary">تعديل الصورة</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">إزالة الصور</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h1 class="text-primary text-center mb-5">معلومات العيادة</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('doctor.update-clinic') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-3 text-md-end">
                                <label for="clinic_name" class="form-label fw-bold">اسم العيادة</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control bg-light" id="clinic_name" name="name" value="{{ $clinic->name ?? 'آثر' }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3 text-md-end">
                                <label for="address" class="form-label fw-bold">العنوان</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control bg-light" id="address" name="address" value="{{ $clinic->address ?? 'جراحة وزراعة الأسنان' }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3 text-md-end">
                                <label class="form-label fw-bold">وقت الدوام</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-5">
                                        <input type="time" class="form-control bg-light" name="opening_time" value="{{ $clinic->opening_time ?? '04:00' }}" required>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="form-label fw-bold">الى</span>
                                    </div>
                                    <div class="col-5">
                                        <input type="time" class="form-control bg-light" name="closing_time" value="{{ $clinic->closing_time ?? '10:00' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5">حفظ التغييرات</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-5 ms-2">الغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
