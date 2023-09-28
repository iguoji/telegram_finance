<?php

namespace App\Telegram;

/**
 * 更新类
 */
class Update
{
    /**
     * 构造函数
     */
    public function __construct(protected array $context)
    {}

    /**
     * 具体消息
     */
    public function getMessage() : array
    {
        return $this->context['message'] ?? $this->context['edited_message'] ?? $this->context['callback_query']['message'] ?? [];
    }

    /**
     * 消息ID
     */
    public function getMessageId() : int
    {
        return $this->getMessage()['message_id'] ?? -1;
    }

    /**
     * 消息实体
     */
    public function getMessageEntities() : array
    {
        return $this->getMessage()['entities'] ?? [];
    }

    /**
     * 文本内容
     */
    public function getText() : string
    {
        return trim($this->getCallbackQuery()['data'] ?? $this->getMessage()['text'] ?? '');
    }

    /**
     * 来源用户
     */
    public function getFrom() : array
    {
        return $this->getCallbackQuery()['from'] ?? $this->getMyChatMemberMessage()['from'] ?? $this->getMessage()['from'] ?? [];
    }

    /**
     * 来源用户ID
     */
    public function getFromId() : int
    {
        return $this->getFrom()['id'];
    }

    /**
     * 来源用户名
     */
    public function getFromUsername() : string
    {
        return $this->getFrom()['username'];
    }

    /**
     * 是否为机器人的群成员身份变化消息
     */
    public function isMyChatMemberMessage() : bool
    {
        return isset($this->context['my_chat_member']);
    }

    /**
     * 获取机器人的群成员身份变化消息
     */
    public function getMyChatMemberMessage() : array
    {
        return $this->context['my_chat_member'] ?? [];
    }

    /**
     * 获取机器人的群成员老身份变化消息
     */
    public function getMyOldChatMember() : array
    {
        return $this->isMyChatMemberMessage() ? $this->getMyChatMemberMessage()['old_chat_member'] : [];
    }

    /**
     * 获取机器人在群里的老身份
     */
    public function getMyOldChatMemberStatus() : string
    {
        return $this->getMyOldChatMember()['status'] ?? Chat::STATUS_MEMBER;
    }

    /**
     * 获取机器人的群成员新身份变化消息
     */
    public function getMyNewChatMember() : array
    {
        return $this->isMyChatMemberMessage() ? $this->getMyChatMemberMessage()['new_chat_member'] : [];
    }

    /**
     * 获取机器人在群里的新身份
     */
    public function getMyNewChatMemberStatus() : string
    {
        return $this->getMyNewChatMember()['status'] ?? Chat::STATUS_MEMBER;
    }

    /**
     * 聊天窗口
     */
    public function getChat() : array
    {
        return $this->getMyChatMemberMessage()['chat'] ?? $this->getMessage()['chat'] ?? [];
    }

    /**
     * 聊天编号
     */
    public function getChatId() : string|int
    {
        return $this->getChat()['id'] ?? -1;
    }

    /**
     * 聊天类型
     */
    public function getChatType() : string
    {
        return $this->getChat()['type'] ?? Chat::TYPE_DEFAULT;
    }

    /**
     * 回调查询
     */
    public function getCallbackQuery() : array
    {
        return $this->context['callback_query'] ?? [];
    }

    /**
     * 获取老组的聊天编号
     */
    public function getMigrateFromChatId() : ?int
    {
        return $this->getMessage()['migrate_from_chat_id'] ?? null;
    }

    /**
     * 获取新组的聊天编号
     */
    public function getMigrateToChatId() : ?int
    {
        return $this->getMessage()['migrate_to_chat_id'] ?? null;
    }

    /**
     * 未知属性
     */
    public function __get(string $key) : mixed
    {
        return $this->context[$key] ?? null;
    }
}
