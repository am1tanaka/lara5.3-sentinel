@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            @if (Sentinel::check()->hasAccess('user.create'))

            <form id="storeUserForm" class="col" role="form" method="POST" action="{{url('users')}}">
                {{ csrf_field() }}

                <div class="panel panel-default">
                    <div class="panel-heading">ユーザー新規登録</div>
                    <div class="panel-body">

                        @include('parts.entry-user', ['no_password' => true])

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 help-block">
                            ＊パスワードを省略すると自動生成します。
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cb_user_new" class="col-md-4 control-label">ロール</label>
                            <div class="col-md-6" id="cb_user_new">
                                @include('parts.role-select', ['user' => false, 'roles' => $roles])
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> ユーザー登録
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            @endif

            {{ $users->links() }}

            <div id="user-list">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>
                                ユーザー名
                            </th>
                            <th>
                                メールアドレス
                            </th>
                            <th>
                                ロール
                            </th>
                            <th colspan='2'>
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <div id="user-list">

            {{ $users->links() }}

        </div>
    </div>
</div>
@endsection
