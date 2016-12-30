<?php

namespace App\Http\Controllers\Sentinel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * コンストラクター
     * ログイン時のみ処理する
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('sentinel.setting');
    }
}
