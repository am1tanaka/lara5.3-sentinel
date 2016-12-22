<?php

namespace App\Http\Controllers\Sentinel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Sentinel;
use Redirect;

class UserController extends Controller
{
    /**
     * コンストラクター
     * 処理に権限チェックのミドルウェアを設定
     */
    public function __construct() {
        $this->middleware('permission:user.view', [
            'only' => [
                'index'
            ]
        ]);
        $this->middleware('permission:user.create', [
            'only' => [
                'store'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Sentinel::check();
        if (!!$user) {
            return view('sentinel.users', [
                'roles' => Sentinel::getRoleRepository()->paginate(config("app.items_per_page")),
                'users' => Sentinel::getUserRepository()->paginate(config("app.items_per_page"))
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $this->validate($request, [
            // nameは必須で、255文字まで
            'name' => 'required|max:255',
            // emailは必須で、emailの形式で、255文字までで、usersテーブル内でユニーク
            'email' => 'required|email|max:255|unique:users',
            // passwordは存在するのであれば、6文字以上255文字以下で、確認欄と一致する必要がある
            'password' => 'between:6,255|confirmed'
        ]);

        // パスワードが無指定の場合は、自動生成する
        $pass = $request->password;
        if (empty($pass)) {
            $pass = str_random(config('auth.password_generate_length'));
        }

        // DBに登録
        $credentials = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $pass,
        ];
        $user = Sentinel::registerAndActivate($credentials);

        // ロールを設定する
        $roles = [];
        $rolename = "";
        if (isset($request['user_new'])) {
            $myrole = Sentinel::findRoleById($request['user_new']-0);
            if ($myrole != null) {
                $myrole->users()->attach($user);
                $rolename = $myrole->name;
            }
        }

        // 操作している管理者にメールで通知する
        Sentinel::check()->notify(new \App\Notifications\UserEntryNotify([
            'subject' => trans('sentinel.user_regist_subject'),
            'blade' => 'sentinel.emails.user-registered',
            'args' => [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $pass,
                'roles' => $rolename,
            ]
        ]));

        // メールを確認して、承認してからログインすることを表示するページへ
        return redirect('users')->with('info', trans('sentinel.user_registered'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
