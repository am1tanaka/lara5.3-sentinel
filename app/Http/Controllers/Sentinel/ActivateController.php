<?php

namespace App\Http\Controllers\Sentinel;

use Activation;
use Sentinel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivateController extends Controller
{
    /**
     * アクティベーション
     */
    protected function activate(Request $request) {
        // ユーザーを取得する
        $user = Sentinel::findByCredentials(['email' => base64_decode($request->email)]);
        if (is_null($user)) {
            return redirect('login')->with(['myerror' => trans('sentinel.invalid_activation_params')]);
        }

        // アクティベーション済みだった場合、そのまま戻る
        if (Activation::completed($user)) {
            return redirect('login');
        }

        // アクティベーションを実行する
        if (!Activation::complete($user, $request->code)) {
            return redirect('login')->with(['myerror' => trans('sentinel.invalid_activation_params')]);
        }

        return redirect('login')->with(['info' => trans('sentinel.activation_done')]);
    }
}
