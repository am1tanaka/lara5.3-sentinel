<?php

use Sentinel;

class UserUpdateCest
{
    private $cres = [
        [
            'name' => 'テスト',
            'password' => 'password'
        ],
    ];

    public function _before(FunctionalTester $I)
    {
        $this->cres[0]['email'] = config('roles.admin_email');
        Sentinel::registerAndActivate($this->cres[0]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', [
            'email' => $this->cres[0]['email'],
            'password' => $this->cres[0]['password']
        ]);
        \PHPUnit_Framework_Assert::assertTrue(Sentinel::inRole("admin"));

        $user = Sentinel::check();
        \PHPUnit_Framework_Assert::assertTrue($user->hasAccess(['user.update']));

        $I->click('ユーザー管理');

        $I->seeElement('#user_'.$user['id'].'_name');

        $I->fillField('#user_'.$user['id'].'_name', '名前変更');
        $I->click('#update_user_'.$user['id']);
/*
        $I->dontSee('ユーザー情報を変更しますか？');
        $I->dontSeeElement('.modal-dialog');

        $I->submitForm('#user-list', [
            'user_'.$user['id'].'_name', '名前変更'
        ]);

*/
        $I->seeElement('.modal-dialog');
/*

        $I->click('変更');
        $I->see('名前変更');


*/

    }
}
