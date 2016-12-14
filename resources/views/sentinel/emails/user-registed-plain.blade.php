<?php
echo "以下の通り、ユーザーを登録しました。\n\n";
echo "・ユーザー名： ", $name, "\n";
echo "・メールアドレス： ", $email, "\n";
echo "・パスワード： ", $password, "\n";
echo "・ロール： ", $roles, "\n\n";
echo "\n----------\n";
echo "[", config('app.name'), "]システムメール\n";
echo "＊本メールは送信専用のものです。返信には使えません。\n";
