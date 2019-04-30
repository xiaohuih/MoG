<?php

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // base tables
        Encore\Admin\Auth\Database\Menu::truncate();
        Encore\Admin\Auth\Database\Menu::insert(
            [
                [
                    "id" => 1,
                    "parent_id" => 0,
                    "order" => 1,
                    "title" => "控制台",
                    "icon" => "fa-dashboard",
                    "uri" => "/",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-17 07:15:38"
                ],
                [
                    "id" => 2,
                    "parent_id" => 0,
                    "order" => 18,
                    "title" => "管理员",
                    "icon" => "fa-tasks",
                    "uri" => NULL,
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 3,
                    "parent_id" => 2,
                    "order" => 19,
                    "title" => "用户",
                    "icon" => "fa-users",
                    "uri" => "auth/users",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 4,
                    "parent_id" => 2,
                    "order" => 20,
                    "title" => "角色",
                    "icon" => "fa-user",
                    "uri" => "auth/roles",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 5,
                    "parent_id" => 2,
                    "order" => 21,
                    "title" => "权限",
                    "icon" => "fa-ban",
                    "uri" => "auth/permissions",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 6,
                    "parent_id" => 2,
                    "order" => 22,
                    "title" => "菜单",
                    "icon" => "fa-bars",
                    "uri" => "auth/menu",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 7,
                    "parent_id" => 2,
                    "order" => 23,
                    "title" => "操作日志",
                    "icon" => "fa-history",
                    "uri" => "auth/logs",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 13,
                    "parent_id" => 0,
                    "order" => 2,
                    "title" => "玩家",
                    "icon" => "fa-user-md",
                    "uri" => "admin/player",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:40:44",
                    "updated_at" => "2019-04-17 06:43:53"
                ],
                [
                    "id" => 14,
                    "parent_id" => 13,
                    "order" => 3,
                    "title" => "查询",
                    "icon" => "fa-search",
                    "uri" => "player/search",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:41:54",
                    "updated_at" => "2019-04-17 07:48:49"
                ],
                [
                    "id" => 15,
                    "parent_id" => 13,
                    "order" => 4,
                    "title" => "排行榜",
                    "icon" => "fa-list-ol",
                    "uri" => "player/ranks",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:42:50",
                    "updated_at" => "2019-04-30 04:53:53"
                ],
                [
                    "id" => 16,
                    "parent_id" => 0,
                    "order" => 7,
                    "title" => "公会",
                    "icon" => "fa-bank",
                    "uri" => "admin/guild",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:47:21",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 17,
                    "parent_id" => 16,
                    "order" => 8,
                    "title" => "查询",
                    "icon" => "fa-search",
                    "uri" => "guild/search",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:48:13",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 18,
                    "parent_id" => 16,
                    "order" => 9,
                    "title" => "排行榜",
                    "icon" => "fa-list-ol",
                    "uri" => "guild/ranks",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:48:53",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 19,
                    "parent_id" => 0,
                    "order" => 10,
                    "title" => "邮件",
                    "icon" => "fa-envelope",
                    "uri" => "mail",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:52:41",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 20,
                    "parent_id" => 0,
                    "order" => 11,
                    "title" => "公告",
                    "icon" => "fa-bullhorn",
                    "uri" => "notice",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:54:36",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 21,
                    "parent_id" => 0,
                    "order" => 12,
                    "title" => "礼包",
                    "icon" => "fa-briefcase",
                    "uri" => "gcode",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 06:58:37",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 22,
                    "parent_id" => 0,
                    "order" => 13,
                    "title" => "配置",
                    "icon" => "fa-gears",
                    "uri" => "admin/config",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 07:17:46",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 23,
                    "parent_id" => 22,
                    "order" => 16,
                    "title" => "计划",
                    "icon" => "fa-clock-o",
                    "uri" => "config/schedules",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 07:18:25",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 24,
                    "parent_id" => 22,
                    "order" => 14,
                    "title" => "宠物",
                    "icon" => "fa-linux",
                    "uri" => "config/pets",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 07:19:21",
                    "updated_at" => "2019-04-29 10:41:02"
                ],
                [
                    "id" => 25,
                    "parent_id" => 22,
                    "order" => 15,
                    "title" => "道具",
                    "icon" => "fa-shopping-bag",
                    "uri" => "config/items",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 07:21:01",
                    "updated_at" => "2019-04-29 10:41:17"
                ],
                [
                    "id" => 26,
                    "parent_id" => 22,
                    "order" => 17,
                    "title" => "角色",
                    "icon" => "fa-user",
                    "uri" => "config/roles",
                    "permission" => NULL,
                    "created_at" => "2019-04-23 10:56:08",
                    "updated_at" => "2019-04-29 10:36:52"
                ],
                [
                    "id" => 28,
                    "parent_id" => 13,
                    "order" => 5,
                    "title" => "宠物",
                    "icon" => "fa-linux",
                    "uri" => "player/pets",
                    "permission" => NULL,
                    "created_at" => "2019-04-29 09:28:09",
                    "updated_at" => "2019-04-30 04:53:53"
                ],
                [
                    "id" => 29,
                    "parent_id" => 13,
                    "order" => 6,
                    "title" => "道具",
                    "icon" => "fa-shopping-bag",
                    "uri" => "player/items",
                    "permission" => NULL,
                    "created_at" => "2019-04-29 10:36:40",
                    "updated_at" => "2019-04-30 04:53:53"
                ]
            ]
        );

        Encore\Admin\Auth\Database\Permission::truncate();
        Encore\Admin\Auth\Database\Permission::insert(
            [
                [
                    "id" => 1,
                    "name" => "All permission",
                    "slug" => "*",
                    "http_method" => "",
                    "http_path" => "*",
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "id" => 2,
                    "name" => "Dashboard",
                    "slug" => "dashboard",
                    "http_method" => "GET",
                    "http_path" => "/",
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "id" => 3,
                    "name" => "Login",
                    "slug" => "auth.login",
                    "http_method" => "",
                    "http_path" => "/auth/login\r\n/auth/logout",
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "id" => 4,
                    "name" => "User setting",
                    "slug" => "auth.setting",
                    "http_method" => "GET,PUT",
                    "http_path" => "/auth/setting",
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "id" => 5,
                    "name" => "Auth management",
                    "slug" => "auth.management",
                    "http_method" => "",
                    "http_path" => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
                    "created_at" => NULL,
                    "updated_at" => NULL
                ]
            ]
        );

        Encore\Admin\Auth\Database\Role::truncate();
        Encore\Admin\Auth\Database\Role::insert(
            [
                [
                    "id" => 1,
                    "name" => "Administrator",
                    "slug" => "administrator",
                    "created_at" => "2019-03-12 08:39:11",
                    "updated_at" => "2019-03-12 08:39:11"
                ]
            ]
        );

        // pivot tables
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 1,
                    "menu_id" => 2,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ]
            ]
        );

        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert(
            [
                [
                    "role_id" => 1,
                    "permission_id" => 1,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ]
            ]
        );

        // finish
    }
}
