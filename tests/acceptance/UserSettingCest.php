<?php


class UserSettingCest
{
    private $cre =
    [
        'name' => 'テスト',
        'password' => 'password',
        'email' => 'a@a.com'
    ];

    public function _before(AcceptanceTester $I)
    {
        // データベースを削除
        Artisan::call('migrate:refresh');

        // テスト環境の設定
        $user = Sentinel::registerAndActivate($this->cre);
        $this->userid = $user['id'];
    }

    public function _after(AcceptanceTester $I)
    {
        // データベースを削除
        //Artisan::call('migrate:refresh');
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        // ログイン
        $I->wantTo(' ログイン');
        $I->amOnPage('/');
        $I->click('Login');
        $I->submitForm('#login-form', [
            'email' => $this->cre['email'],
            'password' => $this->cre['password']
        ]);

        // ユーザー設定画面に
        $I->wantTo(' 設定画面へ移動');
        $I->click('設定');
        $I->expect(' ユーザー設定が表示');
        $I->see('ユーザー設定');
    }
}
