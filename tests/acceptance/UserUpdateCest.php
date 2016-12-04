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

    public function _before(AcceptanceTester $I)
    {
        // テスト環境であることを確認する
        if (DB::getDatabaseName() !== 'lara53_sentinel_test') {
            $I->see('データベースがテスト用のものではないので、中止します。');
        }

        // データベースを削除
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_users')->truncate();
        DB::table('throttle')->truncate();

        // テスト環境の設定my
        $this->cres[0]['email'] = config('roles.admin_email');
        $user = Sentinel::registerAndActivate($this->cres[0]);
        $this->userid = $user['id'];
    }

    public function _after(AcceptanceTester $I)
    {

    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('Login');
        $I->amOnPage('/login');
        $I->submitForm('#loginForm', [
            'email' => $this->cres[0]['email'],
            'password' => $this->cres[0]['password']
        ]);
        $I->click('ユーザー管理');

        $I->wantTo('No Change Dialog.');
        $I->click('#update_user_'.$this->userid);
        $I->seeInPopup('変更点はありません');
        $I->acceptPopup();

        $I->wantTo('Change Name. Click Change Button. Show Dialog.');
        $I->fillField('#user_'.$this->userid.'_name', '名前変更');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 10);

        $I->wantTo('Click Change Button.');
        $I->click('はい');

        $I->see('update');
    }
}
