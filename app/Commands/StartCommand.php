<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\SwitchInlineQueryChosenChat;

class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        // If you use deep-linking, get the parameter like this:
        // $deep_linking_parameter = $this->getMessage()->getText(true);

        $inline_keyboard = new InlineKeyboard(
            [
                ['text' => '当前聊天', 'switch_inline_query_current_chat' => '哈哈哈哈'],
                ['text' => '其他聊天', 'switch_inline_query' => '呵呵呵呵'],
            ], [
                ['text' => 't03', 'SwitchInlineQueryChosenChat' => 'SwitchInlineQueryChosenChat'],
                ['text' => 't04', 'url' => 'https://github.com/php-telegram-bot/example-bot'],
            ]
        );

        return $this->replyToChat(
            '我是自动记账机器人!' . PHP_EOL .
            '详细信息可以查看[官网介绍](' . config('app.url') . ')!'
        , [
            'parse_mode'    =>  'Markdown',
            'reply_markup'  =>  $inline_keyboard,
        ]);
    }
}
