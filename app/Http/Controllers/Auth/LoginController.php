<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Sentinel;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * ログイン処理
     */
    public function login(Request $request) {
        // バリデーション
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|between:6,255',
            'remember' => 'boolean',
        ]);

        // 認証処理
        try {
            $this->userInterface = Sentinel::authenticate([
                'email' => $request['email'],
                'password' => $request['password']
            ], $request['remember']);
        } catch (NotActivatedException $notactivated) {
            return view('auth.login', [
                'myerror' => trans('sentinel.not_activation'),
                'resend_code' => $request['email'],
            ]);
        } catch (ThrottlingException $throttling) {
            return view('auth.login', ['myerror' => trans('sentinel.login_throttling')."[あと".$throttling->getDelay()."秒]"]);
        }

        if (!$this->userInterface) {
            // エラー
            return view('auth.login', ['myerror' => trans('sentinel.login_failed')]);
        }

        return redirect($this->redirectTo);
    }

    /**
     * ログアウト処理
     */
    protected function logout(Request $request) {
        Sentinel::logout();

        return redirect($this->redirectTo);
    }
}
