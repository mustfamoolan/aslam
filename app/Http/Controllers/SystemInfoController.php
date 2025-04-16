<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemInfoController extends Controller
{
    /**
     * عرض صفحة معلومات النظام
     */
    public function index()
    {
        return view('system_info.index');
    }
}
