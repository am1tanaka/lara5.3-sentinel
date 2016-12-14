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
        ],
        [
            'name' => '追加',
            'email' => 'add@test.com'
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
        $I->submitForm('#login-form', [
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
        $I->submitForm('#login-form', [
            'email' => $this->cres[0]['email'],
            'password' => $this->cres[0]['password']
        ]);
        $I->click('ユーザー管理');
        $I->dontSee(trans('sentinel.permission_denied'), '.alert-danger');

        $I->wantTo('ユーザー登録');
        $moderator = Sentinel::findRoleBySlug('moderator');
        $I->selectOption('form input[name=user_new_role]', $moderator->name);
        $I->submitForm('#store-user-form', $this->cres[2]);
        $I->dontSee(trans('sentinel.permission_denied'), '.alert-danger');

        $I->wantTo('cres[2]の登録確認');
        $I->seeRecord('users', array('email' => $this->cres[2]['email']));
    }
}
