<?php

class ActivationUserCest
{
    private $code = "";
    // ユーザーを登録
    private $cre = [
        'name' => 'アクティベーション',
        'email' => 'act@test.com',
        'password' => 'password'
    ];

    public function _before(FunctionalTester $I)
    {
        $user = Sentinel::register($this->cre);

        // アクティベーション作成
        $act = Activation::create($user);
        $this->code = $act->code;
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // アクティベーションの再送チェック
        $I->expect('アクティベーションコードの再送');
        $url = url('register', [base64_encode($this->cre['email'])]);
        $I->amOnPage($url);
        $I->seeInCurrentUrl('/login');
        $I->see(trans('sentinel.after_register'));

        // アクティベーションで無効なemail
        $I->expect('無効なメールアドレスでの要求');
        $url = url('register', [base64_encode('nobody@test.com')]);
        $I->amOnPage($url);
        $I->seeInCurrentUrl('/login');
        $I->see(trans('sentinel.invalid_activation_params'));

        // コード違い
        $I->expect('アクティベーションコード違いエラー');
        $url = url('activate', [base64_encode($this->cre['email']), 'error']);
        $I->amOnPage($url);
        $I->see(trans('sentinel.invalid_activation_params'), '.alert-danger');

        // 成功チェック
        $I->expect('アクティベーション成功');
        $url = url('activate', [base64_encode($this->cre['email']), $this->code]);
        // リンクを amOnPage で表示
        $I->amOnPage($url);
        // 登録完了のメッセージをチェック
        $I->see(trans('sentinel.activation_done'));

        // すでにアクティベーション済みの場合は、普通にログイン画面へ
        $I->expect('アクティベーション済みはログイン画面へ');
        $url = url('activate', [base64_encode($this->cre['email']), 'no']);
        $I->amOnPage($url);
        $I->dontSee(trans('sentinel.activation_done'));
        $I->dontSee(trans('sentinel.invalid_activation_params'));

        // 失敗チェック
        $I->expect('アクティベーションメールもアクティベーションコードも違う');
        $url = url('activate', [base64_encode('nobody@test.com'), 'no']);
        $I->amOnPage($url);
        $I->see(trans('sentinel.invalid_activation_params'), '.alert-danger');

        // アクティベーションの再送チェック
        $I->expect('アクティベーション済みに対する請求');
        $url = url('register', [base64_encode($this->cre['email'])]);
        $I->amOnPage($url);
        $I->seeInCurrentUrl('/login');
        $I->see(trans('sentinel.activation_done'));
    }
}
