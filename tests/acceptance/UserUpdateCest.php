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

        $I->wantTo(' 変更点がない時のダイアログを確認.');
        $I->click('#update_user_'.$this->userid);
        $I->seeInPopup('変更点はありません');
        $I->acceptPopup();

        $I->wantTo(' 名前の変更処理.');
        $I->comment(' 名前を変更して、変更ボタンを押して、ダイアログの表示を確認.');
        $I->fillField('#user_'.$this->userid.'_name', '名前変更');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 10);
        $I->click('はい');

        $I->expect(' 名前が変更されていることを確認.');
        $I->waitForText('名前変更', 5);

        // Emailの変更
        $I->wantTo(' Emailの変更を確認.');
        $I->fillField('#user_'.$this->userid.'_email', 'b@email.com');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 5);
        $I->click('はい');
        $I->expect(' Emailが変更されていることを確認.');
        $I->waitForText('b@email.com', 5);

        $I->wantTo(' ロールの変更を確認.');
        $I->selectOption('form input[name=user_'.$this->userid.'_role]', 'Administrator');
        $I->click('#update_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 5);
        $I->click('はい');
        $I->expect(' ロールが変更されていることを確認.');
        $I->seeOptionIsSelected('form input[name=user_'.$this->userid.'_role]', 'Administrator');

        $I->wantTo(' ユーザー削除の動作確認.');
        $I->click('#delete_user_'.$this->userid);
        $I->waitForElementVisible('.modal-dialog', 5);
        $I->click('はい');
        $I->expect(' 削除されたメッセージが表示されている.');
        $I->see(trans('sentinel.user_deleted'));
    }
}
