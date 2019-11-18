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
                    "updated_at" => "2019-04-17 15:15:38"
                ],
                [
                    "id" => 2,
                    "parent_id" => 0,
                    "order" => 23,
                    "title" => "管理员",
                    "icon" => "fa-tasks",
                    "uri" => NULL,
                    "permission" => "auth.management",
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 3,
                    "parent_id" => 2,
                    "order" => 24,
                    "title" => "用户",
                    "icon" => "fa-users",
                    "uri" => "auth/users",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 4,
                    "parent_id" => 2,
                    "order" => 25,
                    "title" => "角色",
                    "icon" => "fa-user",
                    "uri" => "auth/roles",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 5,
                    "parent_id" => 2,
                    "order" => 26,
                    "title" => "权限",
                    "icon" => "fa-ban",
                    "uri" => "auth/permissions",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 6,
                    "parent_id" => 2,
                    "order" => 27,
                    "title" => "菜单",
                    "icon" => "fa-bars",
                    "uri" => "auth/menu",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 7,
                    "parent_id" => 2,
                    "order" => 28,
                    "title" => "操作日志",
                    "icon" => "fa-history",
                    "uri" => "auth/logs",
                    "permission" => NULL,
                    "created_at" => NULL,
                    "updated_at" => "2019-11-06 11:42:36"
                ],
                [
                    "id" => 13,
                    "parent_id" => 0,
                    "order" => 2,
                    "title" => "玩家",
                    "icon" => "fa-user-md",
                    "uri" => "admin/player",
                    "permission" => "player.management",
                    "created_at" => "2019-04-17 14:40:44",
                    "updated_at" => "2019-05-16 17:18:51"
                ],
                [
                    "id" => 14,
                    "parent_id" => 13,
                    "order" => 4,
                    "title" => "查询",
                    "icon" => "fa-search",
                    "uri" => "player/search",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 14:41:54",
                    "updated_at" => "2019-05-14 09:38:30"
                ],
                [
                    "id" => 15,
                    "parent_id" => 13,
                    "order" => 3,
                    "title" => "排行榜",
                    "icon" => "fa-list-ol",
                    "uri" => "player/ranks",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 14:42:50",
                    "updated_at" => "2019-05-14 09:38:30"
                ],
                [
                    "id" => 16,
                    "parent_id" => 0,
                    "order" => 7,
                    "title" => "公会",
                    "icon" => "fa-bank",
                    "uri" => "admin/guild",
                    "permission" => "guild.management",
                    "created_at" => "2019-04-17 14:47:21",
                    "updated_at" => "2019-05-16 17:18:33"
                ],
                [
                    "id" => 17,
                    "parent_id" => 16,
                    "order" => 9,
                    "title" => "查询",
                    "icon" => "fa-search",
                    "uri" => "guild/search",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 14:48:13",
                    "updated_at" => "2019-05-14 09:38:30"
                ],
                [
                    "id" => 18,
                    "parent_id" => 16,
                    "order" => 8,
                    "title" => "排行榜",
                    "icon" => "fa-list-ol",
                    "uri" => "guild/ranks",
                    "permission" => NULL,
                    "created_at" => "2019-04-17 14:48:53",
                    "updated_at" => "2019-05-14 09:38:30"
                ],
                [
                    "id" => 19,
                    "parent_id" => 0,
                    "order" => 11,
                    "title" => "邮件",
                    "icon" => "fa-envelope",
                    "uri" => "mail",
                    "permission" => "mail.managerment",
                    "created_at" => "2019-04-17 14:52:41",
                    "updated_at" => "2019-05-16 17:17:55"
                ],
                [
                    "id" => 20,
                    "parent_id" => 0,
                    "order" => 12,
                    "title" => "公告",
                    "icon" => "fa-bullhorn",
                    "uri" => "notice",
                    "permission" => "notice.management",
                    "created_at" => "2019-04-17 14:54:36",
                    "updated_at" => "2019-05-16 17:18:09"
                ],
                [
                    "id" => 21,
                    "parent_id" => 0,
                    "order" => 13,
                    "title" => "礼包",
                    "icon" => "fa-briefcase",
                    "uri" => "gcode",
                    "permission" => "gcode.management",
                    "created_at" => "2019-04-17 14:58:37",
                    "updated_at" => "2019-05-16 17:18:19"
                ],
                [
                    "id" => 28,
                    "parent_id" => 13,
                    "order" => 5,
                    "title" => "宠物",
                    "icon" => "fa-linux",
                    "uri" => "player/pets",
                    "permission" => NULL,
                    "created_at" => "2019-04-29 17:28:09",
                    "updated_at" => "2019-04-30 12:53:53"
                ],
                [
                    "id" => 29,
                    "parent_id" => 13,
                    "order" => 6,
                    "title" => "道具",
                    "icon" => "fa-shopping-bag",
                    "uri" => "player/items",
                    "permission" => NULL,
                    "created_at" => "2019-04-29 18:36:40",
                    "updated_at" => "2019-04-30 12:53:53"
                ],
                [
                    "id" => 30,
                    "parent_id" => 16,
                    "order" => 10,
                    "title" => "成员",
                    "icon" => "fa-users",
                    "uri" => "guild/members",
                    "permission" => NULL,
                    "created_at" => "2019-05-05 11:23:32",
                    "updated_at" => "2019-05-05 11:33:30"
                ],
                [
                    "id" => 32,
                    "parent_id" => 0,
                    "order" => 14,
                    "title" => "脚本",
                    "icon" => "fa-scribd",
                    "uri" => "script",
                    "permission" => "script.management",
                    "created_at" => "2019-05-13 11:40:38",
                    "updated_at" => "2019-05-16 17:17:31"
                ],
                [
                    "id" => 33,
                    "parent_id" => 0,
                    "order" => 15,
                    "title" => "运维",
                    "icon" => "fa-opera",
                    "uri" => NULL,
                    "permission" => "om.management",
                    "created_at" => "2019-05-25 23:57:21",
                    "updated_at" => "2019-05-26 00:01:47"
                ],
                [
                    "id" => 34,
                    "parent_id" => 33,
                    "order" => 16,
                    "title" => "计划",
                    "icon" => "fa-calendar",
                    "uri" => "om/schedules",
                    "permission" => "om.management",
                    "created_at" => "2019-05-25 23:58:18",
                    "updated_at" => "2019-06-13 23:15:37"
                ],
                [
                    "id" => 36,
                    "parent_id" => 33,
                    "order" => 20,
                    "title" => "区服列表",
                    "icon" => "fa-navicon",
                    "uri" => "om/servers",
                    "permission" => "om.management",
                    "created_at" => "2019-05-26 00:02:55",
                    "updated_at" => "2019-08-12 10:25:47"
                ],
                [
                    "id" => 38,
                    "parent_id" => 33,
                    "order" => 19,
                    "title" => "公告",
                    "icon" => "fa-bullhorn",
                    "uri" => "om/notices",
                    "permission" => NULL,
                    "created_at" => "2019-05-26 01:37:14",
                    "updated_at" => "2019-08-12 10:25:47"
                ],
                [
                    "id" => 39,
                    "parent_id" => 33,
                    "order" => 17,
                    "title" => "开服",
                    "icon" => "fa-plane",
                    "uri" => "om/startserver",
                    "permission" => "om.management",
                    "created_at" => "2019-06-12 18:04:25",
                    "updated_at" => "2019-06-13 23:15:37"
                ],
                [
                    "id" => 40,
                    "parent_id" => 33,
                    "order" => 18,
                    "title" => "补丁",
                    "icon" => "fa-plus",
                    "uri" => "/om/patches",
                    "permission" => "om.management",
                    "created_at" => "2019-08-12 10:25:35",
                    "updated_at" => "2019-08-12 10:35:57"
                ],
                [
                    "id" => 42,
                    "parent_id" => 33,
                    "order" => 21,
                    "title" => "提审切换",
                    "icon" => "fa-android",
                    "uri" => "/om/audit",
                    "permission" => "om.management",
                    "created_at" => "2019-10-31 15:46:20",
                    "updated_at" => "2019-11-07 17:54:13"
                ],
                [
                    "id" => 43,
                    "parent_id" => 33,
                    "order" => 22,
                    "title" => "商品配置",
                    "icon" => "fa-shopping-cart",
                    "uri" => "/om/products",
                    "permission" => "om.management",
                    "created_at" => "2019-11-05 20:56:01",
                    "updated_at" => "2019-11-07 17:55:07"
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
                ],
                [
                    "id" => 7,
                    "name" => "Zone setting",
                    "slug" => "zone.setting",
                    "http_method" => "",
                    "http_path" => "/zone*",
                    "created_at" => "2019-05-16 16:50:06",
                    "updated_at" => "2019-05-16 17:10:23"
                ],
                [
                    "id" => 8,
                    "name" => "Player management",
                    "slug" => "player.management",
                    "http_method" => "",
                    "http_path" => "/player*",
                    "created_at" => "2019-05-16 16:59:41",
                    "updated_at" => "2019-05-16 17:08:03"
                ],
                [
                    "id" => 9,
                    "name" => "Guild management",
                    "slug" => "guild.management",
                    "http_method" => "",
                    "http_path" => "/guild*",
                    "created_at" => "2019-05-16 17:06:36",
                    "updated_at" => "2019-05-16 17:08:11"
                ],
                [
                    "id" => 10,
                    "name" => "Mail management",
                    "slug" => "mail.managerment",
                    "http_method" => "",
                    "http_path" => "/mail*",
                    "created_at" => "2019-05-16 17:07:49",
                    "updated_at" => "2019-05-16 17:07:49"
                ],
                [
                    "id" => 11,
                    "name" => "Notice management",
                    "slug" => "notice.management",
                    "http_method" => "",
                    "http_path" => "/notice*",
                    "created_at" => "2019-05-16 17:08:39",
                    "updated_at" => "2019-05-16 17:08:39"
                ],
                [
                    "id" => 12,
                    "name" => "GCode management",
                    "slug" => "gcode.management",
                    "http_method" => "",
                    "http_path" => "/gcode*",
                    "created_at" => "2019-05-16 17:09:10",
                    "updated_at" => "2019-05-16 17:09:10"
                ],
                [
                    "id" => 13,
                    "name" => "Script management",
                    "slug" => "script.management",
                    "http_method" => "",
                    "http_path" => "/script*",
                    "created_at" => "2019-05-16 17:09:36",
                    "updated_at" => "2019-05-16 17:09:36"
                ],
                [
                    "id" => 14,
                    "name" => "O&M management",
                    "slug" => "om.management",
                    "http_method" => "",
                    "http_path" => "/om*",
                    "created_at" => "2019-05-26 00:00:39",
                    "updated_at" => "2019-05-26 00:01:24"
                ],
                [
                    "id" => 15,
                    "name" => "Scheduling",
                    "slug" => "ext.scheduling",
                    "http_method" => NULL,
                    "http_path" => "/scheduling*",
                    "created_at" => "2019-05-26 00:03:43",
                    "updated_at" => "2019-05-26 00:03:43"
                ],
                [
                    "id" => 16,
                    "name" => "Mail Approval",
                    "slug" => "mail.approval",
                    "http_method" => "",
                    "http_path" => NULL,
                    "created_at" => "2019-11-15 14:28:35",
                    "updated_at" => "2019-11-15 14:31:29"
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
                    "created_at" => "2019-03-12 16:39:11",
                    "updated_at" => "2019-03-12 16:39:11"
                ],
                [
                    "id" => 2,
                    "name" => "Development Engineer",
                    "slug" => "dev",
                    "created_at" => "2019-05-16 16:45:20",
                    "updated_at" => "2019-05-16 16:45:43"
                ],
                [
                    "id" => 3,
                    "name" => "Operators",
                    "slug" => "ops",
                    "created_at" => "2019-05-16 17:12:11",
                    "updated_at" => "2019-05-16 17:12:11"
                ]
            ]
        );

        // pivot tables
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 3,
                    "menu_id" => 38,
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
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 7,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 4,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 3,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 2,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 8,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 9,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 10,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 11,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 12,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 13,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 2,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 3,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 4,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 7,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 8,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 9,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 10,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 11,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 12,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 13,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ],
                [
                    "role_id" => 3,
                    "permission_id" => 14,
                    "created_at" => NULL,
                    "updated_at" => NULL
                ]
            ]
        );

        // finish
    }
}
