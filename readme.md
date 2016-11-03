# Laravel5.3でSentinelを利用する

[Laravel5.3でSentinelを利用する(1)LaravelとSentinel、Codeceptionのインストール](http://am1tanaka.hatenablog.com/entry/2016/10/14/193615)で紹介しているプロジェクトの完成版です。



# 前提環境
動作確認したのは以下の環境です。

- Laravel5.3
- Sentinel2.0.13以降
- macOS Sierra 10.12.1
- PHP5.6.19
- MySQL5.7.16
- composer version 1.2.1
- Postfixを設定して、macからgmailなどを通してメール送信できる環境が構築できている
- 記事内には登場しないが、MySQLの操作を楽にする Sequel Pro のインストールをオススメ



# macでの動かし方
## プロジェクトのダウンロード
このリポジトリをクローンするか、ZIPダウンロードして、動かしたいフォルダー内に展開します。展開したら、プロジェクトフォルダー内で、以下をターミナルから実行して、環境をインストルします。

```
composer install
```

## データベースを作成する
- MySQLがインストールされて、サービスが動いているものとする(起動は　sudo mysqld_safe　など)
- ターミナルから以下を実行して、パスワードを入力して、 mysql にログイン
```
mysql -u ユーザー名 -p
```
- データベースを作成する。以下は、lara_sentinelというデータベースと、テスト用のlara_sentinel_testを作成する例
```
create database lara_sentinel;
create database lara_sentinel_test;
```
- 操作用のユーザーを作成して、権限を与えておく。以下は、user_lara_sentというローカルホスト上のユーザーを作成する例。パスワードは任意
```
CREATE USER 'user_lara_sent'@'localhost' IDENTIFIED BY 'YourPassword';
```
- 作成したローカルホストのuser_lara_sentユーザーに、lara_sentinelデータベースへのアクセス権限を与える
```
GRANT ALL PRIVILEGES ON lara_sentinel.* TO 'user_lara_sent'@'localhost';
GRANT ALL PRIVILEGES ON lara_sentinel_test.* TO 'user_lara_sent'@'localhost';
exit
```

## 環境設定
- ターミナルで以下を実行して、環境ファイルの雛形を生成して、アプリキーを生成するア
```
cp .env.example .env
php artisan key:generate
```
- .env をエディターで開く
- APP_URLのパラメーター、 http://localhost:8000 にする
- DB_DATABASEを、作成したデータベース名に変更。上記の例の場合 lara_sentinel
- DB_USERNAMEを、作成したユーザー名に変更。上記の例の場合 user_lara_sent
- DB_PASSWORDを、設定したパスワードに変更。任意に設定したパスワードを書く
- メールについて設定する。macのPostfixで送信できる場合は、以下のように設定。MAIL_FROM_ADDRESSとMAIL_FROM_NAMEは適した内容に変更のこと
```
MAIL_DRIVER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=ExampleFrom
```

引き続き、テスト用の環境を作成します。

- ターミナルで以下を実行して、テスト用の環境ファイルを作成
```
cp .env .env.testing
```
- .env.testing をエディターで開いて、 DB_DATABASE の項目を以下のようにテスト用に変更
```
DB_DATABASE=lara_sentinel_test
```
- テストに http://mailtrap.io を利用する場合は、以下の項目も修正。既存のものはそのままにしておく
```
MAIL_HOST=mailtrap.io
MAIL_USERNAME=<MailtrapのUsername>
MAIL_PASSWORD=<MailtrapのPassword>
```
- mailtrap.io の設定をしたい場合は、ブログの[こちら](http://am1tanaka.hatenablog.com/entry/2016/11/03/210100#メールのテスト環境を構築)を参照

## データベースを生成
環境ができたら、以下をターミナルから実行して、データベースにテーブルを生成します。
```
php artisan migrate
```

## テスト
以上で完了。ターミナルで以下を実行することで、テストコードを実行できます。
```
npm test
```

テストが完了したら、 http://mailtrap.io にテストで送信されたメールが届いていることを確認できます。

実際に自分で操作を確認したい手順は以下の通りです。

- ターミナルから以下を実行して、サービスを起動
```
php artisan serve
```

http://localhost:8000 をWebブラウザーで開くと、Laravelのページが表示されます。
- REGISTER で、ユーザー登録ができる
- 登録に使ったメールアドレスに、アクティベーション用のメールが送信される
- 登録したメールを確認して、届いたメールのアクティベーション用のリンクを押す
- Laravelのログインページが開くのでログインを試す
- ログインに成功したら、 /home のページが表示される
- 画面右上に名前が表示される
- 右上の名前をクリックして、 Logout でログアウト
- その他、パスワードのリセット、アクティベーションコードの再送ができる

以上です。



# License
[MIT license](http://opensource.org/licenses/MIT).
