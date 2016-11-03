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
        $I->expect('未登録ユーザーのログイン失敗');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', [
            'email' => 'nobody@test.com',
            'password' => 'notentry'
        ]);
        $I->see(trans('sentinel.login_failed'));

        // ログインの成功テスト
        $I->expect('ログインの実行');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', $this->cre);
        $I->seeInCurrentUrl('/home');

        // POSTでログアウトテスト
        $I->expect('ログアウトのテスト');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', $this->cre);
        $I->submitForm('#logout-form', []);
        $I->seeInCurrentUrl('/login');

        // GETでログアウトテスト
        $I->amOnPage('/logout');
        $I->seeInCurrentUrl('/login');
    }
}
