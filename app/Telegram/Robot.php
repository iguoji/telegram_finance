<?php

namespace App\Telegram;
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
     * Hook地址
     */
    public string $webhook;

    /**
     * 命令列表
     */
    public array $commands = [];

    /**
     * 回调列表
     */
    public array $callbacks = [];

    /**
     * 构造函数
     */
    public function __construct(string $username, string $token = null, array $commands = [], array $callbacks = [])
    {
        // 配置信息
        $this->username = $username;
        if (is_null($token)) {
            $config = config('telegram.bots.' . $username);
            $token = $config['token'];
            $commands = $config['commands'];
            $callbacks = $config['callbacks'];
        }
        $this->token = $token;
        $this->commands = $commands;
        $this->callbacks = $callbacks;
    }

    /**
     * 我的信息
     */
    public function getMe() : array
    {
        return $this->request(__FUNCTION__);
    }

    /**
     * 设置WebHook
     */
    public function setWebhook() : string
    {
        return $this->request(__FUNCTION__, [
            'url'           =>  $this->webhook = route('telegram.hook.' . $this->username),
            'secret_token'  =>  md5($this->token),
        ]);
    }

    /**
     * 删除WebHook
     */
    public function deleteWebhook() : string
    {
        return $this->request(__FUNCTION__);
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
        Log::debug('handle', [$update]);

        // 检查数据
        if (!empty($update['update_id'])) {
            // 普通消息
            if (!empty($update['message'])) {
                // 消息文本
                $text = trim($update['message']['text'] ?? '');
                // 执行命令
                if (str_starts_with($text, '/')) {
                    $inputs = explode(' ', $text);
                    $command = array_shift($inputs);
                    $commandClass = $this->commands[$command] ?? '';
                    if ($commandClass) {
                        new $commandClass($this, $update, $inputs);
                    }
                }
            }
            // 回调消息
            else if (!empty($update['callback_query'])) {
                // 回调数据
                $data = trim($update['callback_query']['data'] ?? '');
                // 执行回调
                $callbackClass = $this->callbacks[$data] ?? '';
                if ($callbackClass) {
                    new $callbackClass($this, $update);
                }
            }
        }

        // 返回结果
        return 'success';
    }

    /**
     * 发送消息
     */
    public function sendMessage(array $data) : mixed
    {
        return $this->request(__FUNCTION__, $data);
    }

    /**
     * 执行请求
     */
    public function request(string $method, array $paramaters = []) : mixed
    {
        // 请求地址
        $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
        // 执行请求
        $info = Http::post($url, $paramaters);
        if (empty($info)) {
            abort(555, 'empty result');
        }
        // 返回结果
        $obj = $info->json();
        if ($obj['ok']) {
            return $obj['result'] === true ? $obj['description'] : $obj['result'];
        } else {
            abort($obj['error_code'], $obj['description']);
        }
    }
}