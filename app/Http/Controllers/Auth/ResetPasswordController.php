<?php

namespace App\Http\Controllers\Auth;

use Sentinel;
use Reminder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * パスワードを再設定するための処理
     * ユーザーが有効で、パスワードが条件に合致していたら、SentinelのReminderを使って処理する
     */
    protected function reset(Request $request) {
        // 古いリマインダーコードを削除
        Reminder::removeExpired();

        // チェック
        $this->validate($request, [
           // emailは必須で、emailの形式で、255文字まで
           // メールアドレスの有無は、不正を避けるためにチェックしない
           'email' => 'required|email|max:255',
           // passwordは必須で、6文字以上255文字以下で、確認欄と一致する必要がある
           'password' => 'required|between:6,255|confirmed',
           // トークンは必須で、32文字
           'token' => 'required|size:32'
       ]);

       // ユーザーを検索
       $user = Sentinel::findByCredentials(['email'=>$request->email]);
       if (is_null($user)) {
           // ユーザーがいなければ成功したような感じにしてログイン画面へ
           return redirect('login')->with(['info'=>trans('sentinel.password_reset_sent')]);
       }

       // リマインダー実行
       $reminder = Reminder::complete($user, $request->token, $request->password);
       if ($reminder) {
           // 成功
           return redirect('login')->with(['info' => trans('sentinel.password_reset_done')]);
       }

       // 失敗
       return redirect('login')->with(['myerror'=>trans('sentinel.password_reset_failed')]);
   }
}
