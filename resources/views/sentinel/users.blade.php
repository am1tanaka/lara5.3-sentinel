@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            @include('parts.info')
            @include('parts.errors')

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
                <form id='user-list-form' method="POST" data-url="{{ url('/') }}">
                    {{ csrf_field() }}
                    <input type="hidden" id="_method" name="_method" value="PUT">
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
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <input type="text" name="user_{{$user->id}}_name" id="user_{{$user->id}}_name" v-model="user_{{$user->id}}_name" data-default="{{$user->name}}" maxlength='255' size='20'>
                                    </td>
                                    <td>
                                        <input type="text" name="user_{{$user->id}}_email" id="user_{{$user->id}}_email" v-model="user_{{$user->id}}_email" data-default="{{$user->email}}" maxlength='255' size='30'>
                                    </td>
                                    <td>
                                        @include('parts.role-select', ['user' => $user, 'roles' => $roles])
                                    </td>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <!--
                                        <button type="button" class="btn btn-primary" id="update_user_{{$user->id}}" data-toggle="modal" data-target="#modalUpdate-{{$user->id}}" v-on:click="updateUser($event,{{$user->id}})">
                                        -->
                                        <button type="button" class="btn btn-primary" id="update_user_{{$user->id}}" v-on:click="updateUser($event,{{$user->id}})">
                                            <i class="fa fa-btn fa-refresh"></i>変更
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" v-on:click="deleteUser($event,{{$user->id}})">
                                            <i class="fa fa-btn fa-trash"></i>削除
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- 確認モーダル-->
                    <modal></modal>

                </form>
            </div>

            {{ $users->links() }}

        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript" src="./js/userList.js"></script>
@endsection
