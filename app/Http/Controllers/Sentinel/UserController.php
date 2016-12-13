<?php

namespace App\Http\Controllers\Sentinel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Sentinel;

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
        $this->middleware('permission:user.update', [
            'only' => [
                'update'
            ]
        ]);
        $this->middleware('permission:user.delete', [
            'only' => [
                'destroy'
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

        // メールで送信する
        Sentinel::check()->notify(new \App\Notifications\UserEntryNotify([
            'subject' => trans('sentinel.user_regist_subject'),
            'blade' => 'sentinel.emails.user-regist-done',
            'args' => [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $pass,
                'roles' => $rolename,
            ]
        ]));

        // メールを確認して、承認してからログインすることを表示するページへ
        return redirect('users')->with('info', trans('sentinel.user_regist_done'));
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
        // ユーザーを検索
        $user = Sentinel::findById($id);
        if (!$user) {
            // 手動でアクセスした場合はユーザーが見つからない可能性があるので、チェックをしておく
            return back()->withInput()->withErrors(['user_not_found' => trans('sentinel.user_not_found')]);
        }

        // 更新したデータがあれば更新する
        $userid = 'user_'.$id."_";
        $changed = [];
        if ((!empty($request[$userid.'name'])) && ($user->name !== $request[$userid.'name'])) {
            $changed['name_changed'] = $user->name." > ".$request[$userid.'name'];
            $user->name = $request[$userid.'name'];
        }
        if ((!empty($request[$userid.'email'])) && ($user->email !== $request[$userid.'email'])) {
            $changed['email_changed'] = $user->email." > ".$request[$userid.'email'];
            $user->email = $request[$userid.'email'];
        }

        if (count($changed) > 0)
        {
            $user->save();
        }

        // ロールのチェック
        $nowroles = "";
        foreach(Sentinel::getRoleRepository()->all() as $role) {
            $idxinrole = $userid.'role';
            $inrole = (!empty($request[$idxinrole] && ($request[$idxinrole]===$role->name)));
            $nowrole = $user->inRole($role->slug);
            if ($nowrole && !$inrole) {
                // ロールを外す
                $changed['role_detach'.$role->id] = $role->name.trans('sentinel.detach_role');
                $role->users()->detach($user);
            }
            else if (!$nowrole && $inrole) {
                // ロールを設定
                $changed['role_attach'.$role->id] = $role->name.trans('sentinel.attach_role');
                $role->users()->attach($user);
            }
        }

        // 結果を表示して戻る
        return back()->withInput()->with(['info' => $changed]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // ユーザーを検索
        $user = Sentinel::findById($id);
        if (!$user) {
            // 手動でアクセスした場合はユーザーが見つからない可能性があるので、チェックをしておく
            return back()->withInput()->withErrors(['user_not_found' => trans('sentinel.user_not_found')]);
        }

        // 削除実行
        $user->delete();

        // 削除完了メッセージを添えて元のページに戻る
        return back()->with(['info' => trans('sentinel.user_delete_done')]);
    }
}
