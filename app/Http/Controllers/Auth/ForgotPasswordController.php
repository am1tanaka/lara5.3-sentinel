<?php

namespace App\Http\Controllers\Auth;

use Reminder;
use Sentinel;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
    protected function sendResetLinkEmail(Request $request) {
        // 古いリマインダーコードを削除
        Reminder::removeExpired();

        // チェック
        $this->validate($request, [
            // emailは必須で、emailの形式で、255文字まで
            // メールアドレスの有無は、不正を避けるためにチェックしない
            'email' => 'required|email|max:255'
        ]);

        // ユーザーを検索
        $user = Sentinel::findByCredentials(['email'=>$request->email]);
        if (is_null($user)) {
            // ユーザーがいなければ成功したような感じにしてログイン画面へ
            return redirect('login')->with(['info'=>trans('sentinel.password_reset_sent')]);
        }

        // リマインダーが作成済みなら、それを再送信する
        $code = "";
        $exists = Reminder::exists($user);
        if ($exists) {
            // すでに設定されているので、リマインダーコードを設定
            $code = $exists->code;
        }
        else {
            // 新規にリマインダーを作成して、コードを返す
            $reminder = Reminder::create($user);
            $code = $reminder->code;
        }

        // メールを送信
        $user->notify(new ResetPasswordNotification($code));

        // 成功したら、login画面へ移動
        return redirect('login')->with(['info'=>trans('sentinel.password_reset_sent')]);
    }
}
