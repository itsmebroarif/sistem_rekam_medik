<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Ganti bahasa aplikasi dan simpan di session.
     */
    public function switchLocale($locale)
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    }
}
