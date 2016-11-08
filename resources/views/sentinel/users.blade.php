@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            @include('parts.errors')

            @if (Sentinel::check()->hasAccess('user.create'))

            <form class="col" role="form" method="POST" action="{{url('users')}}">
                {{ csrf_field() }}

                <div class="panel panel-default">
                    <div class="panel-heading">ユーザー新規登録</div>
                    <div class="panel-body">

                        @include('parts.entry-user')

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
                            <form action="{{ url('users/'.$user->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}

                                <td>
                                    <input type="text" name="user_{{$user->id}}_name" id="user_{{$user->id}}_name" value="{{$user->name}}" maxlength='255' size='20'>
                                </td>
                                <td>
                                    <input type="text" name="user_{{$user->id}}_email" id="user_{{$user->id}}_email" value="{{$user->email}}" maxlength='255' size='30'>
                                </td>
                                <td>
                                    @include('parts.role-select', ['user' => $user, 'roles' => $roles])
                                </td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate-{{$user->id}}">
                                        <i class="fa fa-btn fa-refresh"></i>変更
                                    </button>

                                    <!-- 更新モーダル-->
                                    <div class="modal fade" id="modalUpdate-{{$user->id}}" tabindex="-1" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">ユーザー情報を変更しますか？</h4>
                                          </div>

                                          <div class="modal-footer">
                                              <button type="submit" id="update-{{ $user->id }}" class="btn btn-primary">
                                                  <i class="fa fa-btn fa-check"></i>  はい
                                              </button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">
                                                  <i class="fa fa-btn fa-close"></i>いいえ</button>

                                          </div>
                                        </div><!-- /.modal-content -->
                                      </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                </td>
                            </form>

                            <td>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete-{{$user->id}}">
                                    <i class="fa fa-btn fa-trash"></i>削除
                                </button>
                                <!-- 削除モーダル-->
                                <div class="modal fade" id="modalDelete-{{$user->id}}" tabindex="-1" role="dialog">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">指定のユーザーを削除しますか？</h4>
                                      </div>
                                      <div class="modal-body">
                                          {{$user->name}} ： {{$user->email}}
                                      </div>
                                      <div class="modal-footer">
                                          <form action="{{ url('users/'.$user->id) }}" method="POST">
                                              {{ csrf_field() }}
                                              {{ method_field('DELETE') }}

                                              <button type="submit" id="update-{{ $user->id }}" class="btn btn-primary">
                                                  <i class="fa fa-btn fa-check"></i>  はい
                                              </button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">
                                                  <i class="fa fa-btn fa-close"></i>いいえ</button>
                                          </form>

                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $users->links() }}

        </div>
    </div>
</div>
<input type="hidden" name="_method" value="">
@endsection
