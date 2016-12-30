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
     * ログアウト後に表示するページ
     */
    protected $logoutTo = '/login';

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
                'resend_code' => $request['email']
            ])->withErrors([trans('sentinel.not_activation')]);
        } catch (ThrottlingException $throttling) {
            return view('auth.login')->withErrors([trans('sentinel.login_throttling')."[あと".$throttling->getDelay()."秒]"]);
        }

        if (!$this->userInterface) {
            // エラー
            return view('auth.login')->withErrors([trans('sentinel.login_failed')]);
        }

        // ロールのチェック
        $this->checkAdminMailRoles($request);

        return redirect($this->redirectTo);
    }

    /**
     * ログアウト処理
     */
    protected function logout(Request $request) {
        Sentinel::logout();

        return redirect($this->logoutTo);
    }

    /**
     * 管理者のメールアドレスチェックをして、管理者の時で、ロールがない時は、
     * ロールを整える
     * @return bool true=作成した / false=何もしない
     */
    private function checkAdminMailRoles(Request $request) {
        if (strcmp($request['email'], config('roles.admin_email')) !== 0) {
            return false;
        }

        // ロールがあるかを確認する
        if (!Sentinel::findRoleBySlug('admin')) {
            // ロールがないので、作成する
            $defs = config('roles.default_roles');
            foreach($defs as $k => $v)
            {
                // ロールがなければ作成
                $role = Sentinel::findRoleByName($defs[$k]['name']);
                if (!$role) {
                    Sentinel::getRoleRepository()->createModel()->create($defs[$k]);
                }
                else {
                    // ロールがある場合は、更新
                    $role->permissions = $defs[$k]['permissions'];
                    $role->save();
                }
            }
        }

        // 管理者のメール。管理者ロールが割り当てられている場合は何もしない
        if (Sentinel::inRole('admin')) {
            return false;
        }

        // ユーザーにadminロールを設定する
        $role = Sentinel::findRoleBySlug('admin');
        $role->users()->attach($this->userInterface);
        return true;
    }
}
