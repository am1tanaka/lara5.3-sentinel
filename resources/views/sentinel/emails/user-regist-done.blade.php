<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
</body>
</html>

<p>以下の通り、ユーザーを登録しました。</p>
<br />
<table border='1'>
    <tr><th>ユーザー名</th><td>{{$name}}</td></tr>
    <tr><th>メールアドレス</th><td>{{$email}}</td></tr>
    <tr><th>パスワード</th><td>{{$password}}</td></tr>
    <tr><th>ロール</th><td>{{$roles}}</td></tr>
</table>
<br />
<hr>
<p>[{{config('app.name')}}] システムメール</p>
<p>＊本メールは送信専用のものです。返信には使えません。</p>
