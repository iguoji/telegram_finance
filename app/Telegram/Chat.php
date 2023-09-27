<?php

namespace App\Telegram;

/**
 * 聊天
 */
class Chat {

    /**
     * 类型：所有
     */
    const TYPE_DEFAULT = 'default';

    /**
     * 类型：私聊
     */
    const TYPE_PRIVATE = 'private';

    /**
     * 类型：群聊
     */
    const TYPE_GROUP = 'group';

    /**
     * 类型：超级组
     */
    const TYPE_SUPER_GROUP = 'supergroup';

    /**
     * 类型：频道
     */
    const TYPE_CHANNEL = 'channel';



    /**
     * 状态：创建者
     */
    const STATUS_OWNER = 'creator';

    /**
     * 状态：管理员
     */
    const STATUS_ADMINISTRATOR = 'administrator';

    /**
     * 状态：普通成员
     */
    const STATUS_MEMBER = 'member';

    /**
     * 状态：受限制
     */
    const STATUS_RESTRICTED = 'restricted';

    /**
     * 状态：不是成员，或已离开，或可自行加入
     */
    const STATUS_LEFT = 'left';

    /**
     * 状态：被拉入黑名单
     */
    const STATUS_BANNED = 'kicked';
}