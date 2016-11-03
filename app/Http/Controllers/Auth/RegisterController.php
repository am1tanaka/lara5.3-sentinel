<?php

namespace App\Http\Controllers\Auth;

use Activation;
use Mail;
use Sentinel;
use App\User;
use App\Http\Controllers\Controller;
use App\Notifications\RegisterNotify;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'login';

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
     * ユーザー登録
     */
    protected function register(Request $request)
    {
        $this->validate($request, [
            // nameは必須で、255文字まで
            'name' => 'required|max:255',
            // emailは必須で、emailの形式で、255文字までで、usersテーブル内でユニーク
            'email' => 'required|email|max:255|unique:users',
            // passwordは必須で、6文字以上255文字以下で、確認欄と一致する必要がある
            'password' => 'required|between:6,255|confirmed',
        ]);

        // 情報に問題がなければ、ユーザー登録
        $credentials = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ];
        $user = Sentinel::register($credentials);

        // アクティベーションを作成する
        $activation = Activation::create($user);

        // メールで送信する
        $usermodel = User::where('email', $user->email)->get()[0];
        $usermodel->notify(new RegisterNotify($activation->code));

        // メールを確認して、承認してからログインすることを表示するページへ
        return redirect($this->redirectTo)->with('info', trans('sentinel.after_register'));
    }
}
