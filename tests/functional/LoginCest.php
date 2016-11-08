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
    // 管理者情報
    private $admin = [
        'name' => '管理者',
        'email' => 'admin@email.com',
        'password' => 'password2'
    ];

    public function _before(FunctionalTester $I)
    {
        // ユーザーを登録
        Sentinel::registerAndActivate($this->admin);
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

        // 管理者ではないことを確認
        $I->expect('管理者じゃないテスト');
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("admin"));
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("moderator"));

        // POSTでログアウトテスト
        $I->expect('ログアウトのテスト');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', $this->cre);
        $I->submitForm('#logout-form', []);
        $I->seeInCurrentUrl('/login');

        // GETでログアウトテスト
        $I->amOnPage('/logout');
        $I->seeInCurrentUrl('/login');

        // 管理者テスト
        $I->expect('管理者テスト');
        //// ログイン
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', $this->admin);
        //// ロールのチェック
        \PHPUnit_Framework_Assert::assertTrue(Sentinel::inRole("admin"));
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("moderator"));

        //// メニューチェック
        $I->see('ユーザー管理');
        $I->see('ロール管理');
    }
}
