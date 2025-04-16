@extends('layouts.system_info_layout')

@section('title', 'معلومات النظام')

@section('content')
<!-- كارد معلومات النظام -->
<div class="info-card">
    <div class="info-card-header">
        <h2 class="info-card-title">معلومات النظام</h2>
    </div>

    <div class="info-item">
        <span class="info-label">اسم النظام :</span>
        <span class="info-value">DENTICA .</span>
    </div>

    <div class="info-item">
        <span class="info-label">إصدار النظام :</span>
        <span class="info-value">1.0.0 .</span>
    </div>

    <div class="info-item">
        <span class="info-label">آخر تحديث :</span>
        <span class="info-value">{{ date('d/m/Y') }} : الساعة {{ date('h:i') }} {{ date('a') == 'am' ? 'صباحًا' : 'مساءً' }} .</span>
    </div>

    <div class="info-item">
        <span class="info-label">الميزات الجديدة مع آخر تحديث :</span>
        <span class="info-value">تم إضافة (ميزة الإحصائيات، ميزة إدارة المخزون) و تحسينات عامة .</span>
    </div>

    <div class="info-item">
        <span class="info-label">آخر نسخ احتياطي للبيانات :</span>
        <span class="info-value">لم يتم النسخ ! .</span>
    </div>
</div>

<!-- كارد فريق التطوير -->
<div class="info-card">
    <div class="info-card-header">
        <h2 class="info-card-title">فريق التطوير</h2>
        <img src="{{ asset('images/swa-logo.png') }}" alt="Swa Company" class="company-logo">
    </div>

    <div class="info-item">
        <span class="info-label">الشركة المطورة للنظام :</span>
        <span class="info-value">Swa Company .</span>
    </div>

    <div class="info-item">
        <span class="info-label">البريد الإلكتروني :</span>
        <span class="info-value">swaInfo@gmail.com .</span>
    </div>

    <div class="info-item">
        <span class="info-label">الانستقرام :</span>
        <span class="info-value">swa .</span>
    </div>

    <div class="info-item">
        <span class="info-value">لطرح تحسيناتكم على النظام ، أو أي مشاكل و استفسارات تواجهكم تواصلوا معنا ، أطيب التحيات .</span>
    </div>
</div>
@endsection
