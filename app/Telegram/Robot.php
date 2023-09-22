<?php

namespace App\Telegram;

use App\Jobs\SendMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * 机器人
 */
class Robot
{
    /**
     * 用户名
     */
    public string $username;

    /**
     * 密钥
     */
    public string $token;

    /**
     * 命令列表
     */
    public array $commands = [];

    /**
     * 回调列表
     */
    public array $callbacks = [];

    /**
     * 键盘列表
     */
    public array $keyboard = [];

    /**
     * 构造函数
     */
    public function __construct(string $username)
    {
        // 配置信息
        $this->username = $username;
        $config = config('telegram.bots.' . $username);
        $this->token = $config['token'];
        $this->commands = $config['commands'];
        $this->callbacks = $config['callbacks'];
        $this->keyboard = $config['keyboard'];

        // 回复键盘
        $this->keyboard = array_map(fn($r) => array_map(fn($v) => ['text' => $v], $r), $this->keyboard);

    }

    /**
     * 检查请求
     */
    public function check(string $secret) : static
    {
        abort_if($secret != md5($this->token), 401, 'Origin Unauthorized');

        return $this;
    }

    /**
     * 处理句柄
     */
    public function handle(string $secret, array $update) : mixed
    {
        // 检查令牌
        $this->check($secret);

        // 日志记录
        Log::debug('robot handle', $update);

        // 检查数据
        if (!empty($update['update_id'])) {

            // 具体消息
            $message = $update['message'] ?? $update['callback_query']['message'] ?? [];
            // 来源用户
            $user = $update['callback_query']['from'] ?? $message['from'] ?? [];
            // 聊天窗口
            $chat = $message['chat'] ?? [];
            // 文本内容
            $text = trim($message['text'] ?? $update['callback_query']['data'] ?? '');

            // 检查指令
            foreach ($this->commands as $usage => $class) {
                // 如果以该指令开头
                if (str_starts_with($text, $usage)) {
                    $ins = new $class($this);
                    return $ins->execute($text, $user, $chat, $message);
                }
            }

            // 检查回调
            foreach ($this->callbacks as $usage => $class) {
                // 如果以该指令开头
                if (str_starts_with($text, $usage)) {
                    $ins = new $class($this);
                    return $ins->execute($text, $user, $chat, $message);
                }
            }
        }

        // 返回结果
        return 'success';
    }

    /**
     * 解析参数
     */
    public function parseArgs(string $text, string $usage) : bool|string
    {
        return str_starts_with($text, $usage) ? mb_substr($text, mb_strlen($usage)) : false;
    }

    /**
     * 处理普通消息
     */
    public function handleMessage(array $context)
    {
        // 消息文本
        $text = trim($context['text'] ?? '');
        // 执行命令
        if (str_starts_with($text, '/')) {
            $inputs = explode(' ', $text);
            $command = array_shift($inputs);
            $commandClass = $this->commands[$command] ?? '';
            if ($commandClass) {
                $ins = new $commandClass($this);
                $ins->execute($context, $inputs);
            }
        } else {
            // 执行回调
            $callbackClass = $this->callbacks[$text] ?? '';
            if ($callbackClass) {
                $ins = new $callbackClass($this);
                $ins->execute($context);
            }
        }
    }

    /**
     * 处理回调消息
     */
    public function handleCallbackQuery(array $context)
    {

    }

    /**
     * 发送消息
     */
    public function sendMessage(array $content = []) : mixed
    {
        return SendMessage::dispatch($this, $content);
    }

    /**
     * 执行请求
     */
    public function request(string $method, array $paramaters = []) : mixed
    {
        // 请求地址
        $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
        // 执行请求
        $info = Http::timeout(3)->retry(3, 1000)->asJson()->post($url, $paramaters);
        if (empty($info)) {
            abort(555, 'empty result');
        }
        // 返回结果
        $obj = $info->json();
        Log::debug('request', [$url, $paramaters, $obj]);
        if ($obj['ok']) {
            return $obj['result'] === true ? ($obj['description'] ?? '') : $obj['result'];
        } else {
            abort($obj['error_code'], $obj['description'] ?? $obj['error_code']);
        }
    }

    /**
     * 方法调用
     */
    public function call(string $name, array $content = []) : mixed
    {
        // 发送消息
        if ($name == 'sendMessage') {
            // 不存在回复标记、发送键盘
            if (empty($content['reply_markup'])) {
                // 回复标记
                $content['reply_markup'] = $content['reply_markup'] ?? [];
                // 键盘
                $content['reply_markup']['keyboard'] = $this->keyboard;
                // JSON
                $content['reply_markup'] = json_encode($content['reply_markup']);
            }
        }

        // 请求并返回结果
        return $this->request($name, $content);
    }

    /**
     * 未知方法
     */
    public function __call(string $name, array $arguments = []) : mixed
    {
        return $this->call($name, $arguments[0] ?? []);
    }
}
