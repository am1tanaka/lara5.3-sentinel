<?php
/**
 * ロール用の設定ファイル
 * @copyright 2016 YuTanaka@AmuseOne
 */

return [
    // 管理者用EMailアドレスがあれば設定
    'admin_email' => env('ADMIN_EMAIL', ''),

    // デフォルトのロールに対する権限
    "default_roles" => [
        "default_admin_roles" => [
            "name" => "Administrator",
            "slug" => "admin",
            "permissions" => [
                "user.create" => true,
                "user.delete" => true,
                "user.view" => true,
                "user.update" => true,
                "role.create" => true,
                "role.delete" => true,
                "role.view" => true,
                "role.update" => true,
            ]
        ],
        "default_moderator" => [
            "name" => "Moderator",
            "slug" => "moderator",
            "permissions" => [
                "user.create" => true,
                "user.delete" => false,
                "user.view" => true,
                "user.update" => false,
                "role.create" => false,
                "role.delete" => false,
                "role.view" => false,
                "role.update" => false,
            ]
        ]
    ]
];
