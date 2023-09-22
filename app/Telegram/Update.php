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
        return $this->context['message'] ?? $this->context['callback_query']['message'] ?? [];
    }

    /**
     * 消息ID
     */
    public function getMessageId() : int
    {
        return $this->getMessage()['message_id'] ?? -1;
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
        return $this->getCallbackQuery()['from'] ?? $this->getMessage()['from'] ?? [];
    }

    /**
     * 来源用户ID
     */
    public function getFromId() : int
    {
        return $this->getFrom()['id'];
    }

    /**
     * 聊天窗口
     */
    public function getChat() : array
    {
        return $this->getMessage()['chat'] ?? [];
    }

    /**
     * 聊天编号
     */
    public function getChatId() : string|int
    {
        return $this->getChat()['id'] ?? 1234;
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
}