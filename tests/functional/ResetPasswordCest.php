<?php

use Sentinel;
use Reminder;

class ResetPasswordCest
{
    private $cre = [
        'name' => 'パスワード再発行',
        'email' => 'reset@test.com',
        'password' => 'password'
    ];

    public function _before(FunctionalTester $I)
    {
        // ユーザーを作成
        Sentinel::registerAndActivate($this->cre);
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // 存在しないメールへのリクエスト
        $I->wantTo(' 存在しないメールへのリクエストの動作確認.');
        $I->amOnPage('/password/reset');
        $I->submitForm('#send-reset-form', [
            'email' => 'nobody@test.com'
        ]);
        $I->seeInCurrentUrl('/login');

        // メール発行(resetとemailのテスト)
        $I->wantTo(' メール発行(resetとemailのテスト)');
        $I->amOnPage('/password/reset');
        $I->submitForm('#send-reset-form', [
            'email' => $this->cre['email']
        ]);
        $I->seeInCurrentUrl('login');
        $I->see(trans('sentinel.password_reset_sent'));

        // メール発行テスト
        $I->expect(' 登録したemailアドレスが見つかる.');
        $user = Sentinel::findByCredentials($this->cre);
        \PHPUnit_Framework_Assert::assertEquals($user->email, $this->cre['email']);
        $I->expect(' 登録したユーザーのリマインダーが登録されている.');
        $reminder = Reminder::exists($user);
        \PHPUnit_Framework_Assert::assertNotFalse($reminder);

        // 不正なトークンでのアクセスして、パスワードの変更を試みる
        $I->wantTo(' 不正なトークンでアクセスして、パスワードの変更を試みた時に失敗.');
        $I->amOnPage('/password/reset/01234567891123456789212345678931');
        $I->submitForm('#reset-password-form', [
            'email' => $this->cre['email'],
            'password' => $this->cre['password'],
            'password_confirmation' => $this->cre['password'],
        ]);
        $I->see(trans('sentinel.password_reset_failed'));

        // 正しいトークンでのアクセスして、パスワード確認のミス
        $I->wantTo(' 正しいトークンでアクセスして、パスワードの確認が間違いの時の動作.');
        $I->amOnPage('/password/reset/'.$reminder->code);
        $I->submitForm('#reset-password-form', [
            'email' => $this->cre['email'],
            'password' => $this->cre['password'],
            'password_confirmation' => 'invalidinvalid',
        ]);
        $I->see('does not match');

        // 正しいトークンでのアクセスして、パスワード変更
        $I->wantTo(' パスワード変更の実行確認.');
        $I->amOnPage('/password/reset/'.$reminder->code);
        $I->submitForm('#reset-password-form', [
            'email' => $this->cre['email'],
            'password' => 'resetpassword',
            'password_confirmation' => 'resetpassword',
        ]);
        $I->see(trans('sentinel.password_reset_done'));

        // パスワードが変更されたことを確認
        $I->expect(' 変更後のデータが見つかる.');
        $check = [
            'email' => 'reset@test.com',
            'password' => 'resetpassword'
        ];
        \PHPUnit_Framework_Assert::assertNotFalse(Sentinel::authenticate($check));
        // パスワードが変更されたことを確認
        $I->expect(' パスワードが変更されていることを確認.');
        $check = [
            'email' => 'reset@test.com',
            'password' => $this->cre['password']
        ];
        \PHPUnit_Framework_Assert::assertFalse(Sentinel::authenticate($check));
    }
}
