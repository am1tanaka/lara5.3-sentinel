<?php

use Sentinel;

class UserUpdateCest
{
    private $cres = [
        [
            'name' => 'テスト',
            'password' => 'password'
        ],
        [
            'name' => 'ユーザー',
            'email' => 'a@email.com',
            'password' => 'password'
        ]
    ];

    public function _before(AcceptanceTester $I)
    {
        // データベースを削除
        Artisan::call('migrate:refresh');

        // テスト環境の設定
        $this->cres[0]['email'] = config('roles.admin_email');
        Sentinel::registerAndActivate($this->cres[0]);

        // データ変更用のユーザーを追加
        $user = Sentinel::registerAndActivate($this->cres[1]);
        $this->userid = $user['id'];
    }

    public function _after(AcceptanceTester $I)
    {
        // データベースを削除
        Artisan::call('migrate:refresh');
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('Login');
        $I->amOnPage('/login');
        $I->submitForm('#login-form', [
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
        $I->click('はい');

        $I->expect('Changed Name.');
        $I->waitForText('名前変更', 10);

        $I->wantTo('Change Email.');
        $I->fillField('#user_'.$this->userid.'_email', 'b@email.com');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 10);
        $I->click('はい');
        $I->expect('Changed Email.');
        $I->waitForText('b@email.com', 10);

        $I->wantTo('Change Role.');
        $I->selectOption('form input[name=user_'.$this->userid.'_role]', 'Administrator');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 10);
        $I->click('はい');
        $I->expect('Changed Role.');
        $I->seeOptionIsSelected('form input[name=user_'.$this->userid.'_role]', 'Administrator');

        $I->wantTo('Delete User.');
        $I->click('#delete_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 10);
        $I->click('はい');
        $I->expect('See Deleted Message.');
        $I->see(trans('sentinel.user_delete_done'));
    }
}
