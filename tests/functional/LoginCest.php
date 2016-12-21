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
        'password' => 'password2'
    ];

    public function _before(FunctionalTester $I)
    {
        // ユーザーを登録
        $this->admin['email'] = config('roles.admin_email');
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

        // 管理者ではないことを確認
        $I->wantTo(' 管理者でもモデレーターでもないことをテスト');
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("admin"));
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("moderator"));

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

        // 管理者テスト
        $I->wantTo(' 管理者用アカウントでログインして、adminになることを確認');
        //// ログイン
        $I->amOnPage('/login');
        $I->submitForm('#login-form', $this->admin);
        //// ロールのチェック
        \PHPUnit_Framework_Assert::assertTrue(Sentinel::inRole("admin"));
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::inRole("moderator"));

        //// メニューチェック
        $I->wantTo(' メニューが表示されたことを確認');
        $I->see('ユーザー管理');
        $I->see('ロール管理');
    }
}
