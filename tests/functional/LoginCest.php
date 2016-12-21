<?php

use Activation;
use Sentinel;

class LoginCest
{
    // ユーザー情報
    private $cre = [
        'name' => 'ログイン',
        'email' => 'login@test.com',
        'password' => 'password'
    ];

    public function _before(FunctionalTester $I)
    {
        // ユーザーを登録
        Sentinel::registerAndActivate($this->cre);

    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // 無効なログインテスト
        $I->wantTo(' 未登録ユーザーのログイン失敗.');
        $I->amOnPage('/login');
        $I->submitForm('#login-form', [
            'email' => 'nobody@test.com',
            'password' => 'notentry'
        ]);
        $I->see(trans('sentinel.login_failed'));

        // ログインの成功テスト
        $I->wantTo(' ログイン動作の確認.');
        $I->amOnPage('/login');
        $I->submitForm('#login-form', $this->cre);
        $I->seeInCurrentUrl('/home');

        // POSTでログアウトテスト
        $I->wantTo(' ログアウトの動作確認.');
        $I->amOnPage('/login');
        $I->submitForm('#login-form', $this->cre);
        $I->submitForm('#logout-form', []);
        $I->seeInCurrentUrl('/login');

        // GETでログアウトテスト
        $I->wantTo(' GETでのログアウトの動作確認.');
        $I->amOnPage('/logout');
        $I->seeInCurrentUrl('/login');
    }
}
