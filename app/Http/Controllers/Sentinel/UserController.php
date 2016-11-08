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
        /*
        $this->middleware('permission:user.create', [
            'only' => [
                'store'
            ]
        ]);
        */
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
        //
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
