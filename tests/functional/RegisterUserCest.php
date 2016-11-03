<?php


class RegisterUserCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // 空欄での登録試みが失敗するのを確認
        $I->amOnPage('/register');
        $I->submitForm('#registerUserForm', [
        ]);
        $I->seeInCurrentUrl('register');
        $I->see('name', '.help-block');
        $I->see('email', '.help-block');
        $I->see('password', '.help-block');

        // パスワードの不一致を確認
        $I->amOnPage('/register');
        $I->submitForm('#registerUserForm', [
            'name' => 'テスト名前2',
            'email' => 'test2@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'not'
        ]);
        $I->seeInCurrentUrl('register');
        $I->see('password', '.help-block');

        // 登録成功
        $I->amOnPage('/register');
        $I->submitForm('#registerUserForm', [
            'name' => 'テスト名前',
            'email' => 'test@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass'
        ]);
        $I->seeInCurrentUrl('login');
        $I->see(trans('sentinel.after_register'));
    }
}
