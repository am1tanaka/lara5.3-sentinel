<?php

use Sentinel;

class UserEntryCest
{
    private $cres = [
        [
            'name' => '管理者',
            'password' => 'password'
        ],
        [
            'name' => '一般ユーザー',
            'email' => 'user@test.com',
            'password'=>'password'
        ]
    ];

    public function _before(FunctionalTester $I)
    {
        $this->cres[0]['email'] = config('roles.admin_email');
        Sentinel::registerAndActivate($this->cres[0]);
        Sentinel::registerAndActivate($this->cres[1]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // 一般ユーザー
        $I->expect('ユーザー一覧権限なし');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', [
            'email' => $this->cres[1]['email'],
            'password' => $this->cres[1]['password']
        ]);
        $I->amOnPage('/login');
        $I->amOnPage('/users');
        $I->see(trans('sentinel.permission_denied'), '.alert-danger');
        $I->amOnPage('/logout');

        // 管理者テスト
        $I->expect('ユーザー一覧表示');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', [
            'email' => $this->cres[0]['email'],
            'password' => $this->cres[0]['password']
        ]);
        $I->click('ユーザー管理');
        $I->dontSee(trans('sentinel.permission_denied'), '.alert-danger');
    }
}
