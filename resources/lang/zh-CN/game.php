<?php

return [
    'welcome'               => '欢迎使用:appname',
    'zone'                  => '区',
    'mail'                  => '邮件',
    'notice'                => '公告',
    'player'                => '玩家',
    'guild'                 => '公会',
    'rank'                  => '排行榜',
    'gcode'                 => '礼包码',
    'pet'                   => '精灵',
    'pet_desc'              => '精灵  注意:DM暂时不可用',
    'item'                  => '道具',
    'item_desc'             => '道具  注意:DM暂时不可用',
    'schedule'              => '计划',
    'startserver'           => '开服',
    'patch'                 => '补丁',
    'audit'                 => '提审',
    'products'              => '商品',
    'yes'                   => '是',
    'no'                    => '否',
    'update_time'           => '修改时间',
    'remove_item'           => '删除玩家物品',
    'info'                  => [
        'zone'          => '区服',
        'version'       => '版本',
        'type'          => '类型',
        'name'          => '名称',
        'account'       => '账号',
        'role'          => '主角',
        'level'         => '等级',
        'vip'           => 'VIP',
        'power'         => '战力',
        'gold'          => '金币',
        'diamond'       => '钻石',
        'exp'           => '经验',
        'guild'         => '公会',
        'arenascore'    => '竞技场积分',
        'parkourscore'  => '跑酷积分',
        'recharge'      => '充值',
        'consume'       => '消费',
        'paperlevel'    => '通行证',
        'createtime'    => '创建时间',
        'offlinetime'   => '下线时间',
        'status'        => '状态',
        'online'        => '在线',
        'offline'       => '离线',
        'forbid'        => '封禁',
        'rank'          => '排名',
        'value'         => '数值',
        'flag'          => '旗帜',
        'declaration'   => '宣言',
        'fund'          => '资金',
        'joinlimit'     => '入会等级',
        'membercount'   => '成员数量',
        'weekly_contribution'   => '周贡献',
        'total_contribution'   => '总贡献',
        'member'        => '成员',
        'leader'        => '会长',
        'viceleader'    => '副会长',
        'elite'         => '精英',
        'authority'     => '职位',
        'title'         => '标题',
        'content'       => '内容',
        'attachments'   => '附件',
        'receivers'     => '收件人',
        'sendtime'      => '发送时间',
        'starttime'     => '开始时间',
        'begintime'     => '开始时间',
        'endtime'       => '结束时间',
        'interval'      => '间隔',
        'platform'      => '渠道',
        'group'         => '组号',
        'key'           => '密钥',
        'mail'          => '邮件',
        'star'          => '星级',
        'quality'       => '训练等级',
        'nature'        => '天性',
        'count'         => '数量',
        'is_equiped'    => '是否装备',
        'equiped'       => '已装备',
        'unequiped'     => '未装备',
        'published'     => '已发布',
        'unpublished'   => '未发布',
        'sent'          => '已发送',
        'unsent'        => '未发送',
        'server'        => '服务器',
        'state'         => '状态',
        'show'          => '显示',
        'cmd'           => '命令',
        'cron'          => 'Cron表达式',
        'lastrun'       => '上次运行时间',
        'nextrun'       => '下次运行时间',
        'opentime'      => '开启时间',
        'executed'      => '已执行',
        'unexecuted'    => '未执行',
        'script'        => '脚本',
        'file'          => '文件',
        'surl'          => '链接',
        'package'       => '包名',
        'unapproved'    => '未审核',
        'revoked'       => '已撤回',
        'itemid'        => '物品实例id',
        'configid'      => '配置id',
        'is_setup'      => '已装备',
        'added'         => '已添加',
    ],
    'actions'               => [
        'title_confirm' => '确认:action?',
        'kickout'       => '踢下线',
        'publish'       => '发布',
        'send'          => '发送',
        'approval'      => '审核通过',
        'kick'          => '踢出',
        'revoke'        => '撤回',
        'perform'       => '执行',
        'download'      => '下载',
        'start'         => '开启',
        'remove'        => '删除'
    ],
    'ranks'                 => [
        'level'         => '等级',
        'power'         => '战力',
        'recharge'      => '充值',
        'consume'       => '消费',
        'arena'         => '竞技场',
        'parkour'       => '跑酷',
    ],
    'mails'                 => [
        'normal'        => '普通',
        'global'        => '全服',
    ],
    'gcodes'                => [
        'nolimit'       => '无限制',
        'once'          => '单次',
    ],
    'helps'                 => [
        'receivers'     => '多个玩家ID以分号[;]分隔，全服玩家填星号[*]',
        'items'         => '道具ID和数量以逗号[,]分隔，多个道具以[;]分隔',
        'interval'      => '单为（秒），立即发送填0',
        'gcode_group'   => '玩家同一礼包组只能领取一次，0：无限制',
        'sendtime'      => '不填则代表立即发送',
        'opentime'      => '不填则代表需要手动开启',
        'nolimit'       => '不填则代表无限制',
        'remove_item'   => '涉及装备物品,当前只能一次删除一件物品',
        'remove_item_config'   => '会根据配置id判断物品类型进行删除操作,所以一定不可以错,如果是装备物品,必须填写itemid,其他物品itemid可填0',
        'remove_item_count'   => '如果是装备而不是普通物品,则一次只能根据物品实例id删除一个',
    ],
    'servers'               => [
        'client'        => '客户端',
        'scene'         => '场景服',
        'session'       => '会话服',
    ],
    'serverstates'          => [
        'new'           => '新服',
        'burst'         => '爆满',
        'hot'           => '火爆',
        'maintain'      => '维护',
        'hide'          => '隐藏'
    ],
    'notices'               => [
        'maintain'      => '维护',
        'update'        => '更新',
        'operation'     => '运营',
        'mandatory'     => '强更',
    ],
    'select_zone'       => '请选择区服',
    'hint'               => [
        'go_check' => '请去审核',
        'remove_item_hint'         => '玩家离线时进行的删除,会在玩家再次上线时才生效. 添加请求时不支持去重,请注意尽量不要重复删除',
        'remove_value_hint'        => '如要删除玩家资源,请在删除道具界面操作',
    ],
];
