<?php

namespace App\Telegram;

/**
 * 聊天
 */
class Chat {

    /**
     * 所有
     */
    const TYPE_DEFAULT = 'default';

    /**
     * 私聊
     */
    const TYPE_PRIVATE = 'private';

    /**
     * 群聊
     */
    const TYPE_GROUP = 'group';

    /**
     * 超级组
     */
    const TYPE_SUPER_GROUP = 'supergroup';

    /**
     * 频道
     */
    const TYPE_CHANNEL = 'channel';
}