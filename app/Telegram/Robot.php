<?php

namespace App\Telegram;

use App\Jobs\SendMessage;
use Illuminate\Support\Facades\Cache;
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
     * 匹配列表
     */
    public array $matches = [];

    /**
     * 拥有参数的匹配列表
     */
    public array $hasArgumentMatches = [];

    /**
     * 试用时间 单位：秒
     */
    public int $trial_expire;

    /**
     * USDT ERC20
     */
    public string $erc20;

    /**
     * USDT TRC20
     */
    public string $trc20;

    /**
     * 私聊配置
     */
    public array $private = [];

    /**
     * 群聊配置
     */
    public array $group = [];

    /**
     * 构造函数
     */
    public function __construct(array|string $bot = null)
    {
        if ($bot) {
            // 配置信息
            $bot = is_array($bot) ? $bot : config('telegram.bots.' . $bot);
            if (!empty($bot)) {
                // 更新配置
                $this->username = $bot['username'];
                $this->token = $bot['token'];
                $this->trial_expire = $bot['trial_expire'];
                $this->trc20 = $bot['trc20'];
                $this->erc20 = $bot['erc20'];
                $this->commands = $bot['commands'];

                // 匹配列表
                foreach ($bot['matches'] as $abstract => $class) {
                    if (str_ends_with($abstract, '*')) {
                        $command = mb_substr($abstract, 0, -1);
                        $this->matches[$command] = $class;
                        $this->hasArgumentMatches[$command] = $class;
                    } else {
                        $this->matches[$abstract] = $class;
                    }
                }

                // 私聊配置
                $this->private = $bot['private'];

                // 群聊配置
                $this->group = $bot['group'];
            }
        }
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
     * 匹配模式
     */
    public function match(string $text) : array
    {
        // 循环匹配
        foreach ($this->matches as $abstract => $class) {
            // 直接相等
            if ($abstract == $text) {
                // 返回结果
                return [$abstract, $class, false];
            }
            // 开头相等 并 支持参数
            if (str_starts_with($text, $abstract) && isset($this->hasArgumentMatches[$abstract])) {
                return [$abstract, $class, true];
            }
        }

        // 没有找到
        return [null, null, null];
    }

    /**
     * 处理句柄
     */
    public function handle(string $secret, array $context) : mixed
    {
        // 检查令牌
        $this->check($secret);

        // 日志记录
        Log::debug('robot handle', $context);

        // 检查数据
        if (!empty($context['update_id'])) {
            // 消息对象
            $update = new Update($context);
            // 文本内容
            $text = $update->getText();
            // 匹配模式
            list($command, $commandClass, $hasArgument) = $this->match($text);
            Log::debug('robot handle 1', [$command, $commandClass, $text, $this->matches]);
            if ($command && $commandClass) {
                // 私聊
                if ($update->getChatType() == Chat::TYPE_PRIVATE) {
                    // 聊天配置
                    $config = $this->private['default'];
                    // 如果是高级用户
                    if (Cache::get('identity:private:premium:' . $update->getFromId())) {
                        $config = $this->private['premium'];
                    }
                    // 可用的命令列表
                    $matches = $config['matches'];

                    Log::debug('robot handle 2', [$command, $hasArgument, $matches]);
                    
                    // 存在使用该命令的资格
                    if (in_array($command, $matches) || ($hasArgument && in_array($command . '*', $matches))) {
                        // 解析参数
                        $argument = null;
                        if ($hasArgument) {
                            $argument = mb_substr($text, mb_strlen($command));
                        }
                        // 执行命令
                        return (new $commandClass($this, $update, $config))->handle($argument);
                    }
                } else {
                    // 获取身份

                }
            }
            // $matches = $update->getChatType() == Chat::TYPE_PRIVATE ? ;
            // foreach ($variable as $key => $value) {
            //     # code...
            // }
            // 检查指令
            // foreach ($this->commands as $usage => $class) {
            //     // 如果以该指令开头
            //     if (str_starts_with($text, $usage)) {
            //         $ins = app($this->username . '.' . $usage);
            //         //$ins = new $class($this, $update);
            //         $ins->setRobot($this)->setUpdate($update);
            //         if ($ins->validate()) {
            //             return $ins->execute();
            //         }
            //     }
            // }

            // // 检查回调
            // foreach ($this->callbacks as $usage => $class) {
            //     // 如果以该指令开头
            //     if (str_starts_with($text, $usage)) {
            //         $ins = app($this->username . '.' . $usage);
            //         //$ins = new $class($this, $update);
            //         $ins->setRobot($this)->setUpdate($update);
            //         if ($ins->validate()) {
            //             return $ins->execute();
            //         }
            //     }
            // }
        }

        // 返回结果
        return 'success';
    }

    /**
     * 发送消息
     */
    public function sendMessage(array $content = []) : mixed
    {
        // 安排在队列中执行
        // SendMessage::dispatch($this, $content);
        $this->call('sendMessage', $content);
        return true;
    }

    /**
     * 执行请求
     */
    public function request(string $method, array $paramaters = []) : mixed
    {
        // 请求地址
        // $url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
        $url = 'http://127.0.0.1:8081/bot' . $this->token . '/' . $method;
        // 执行请求
        $info = Http::timeout(3)->retry(3, 1000, throw: false)->asJson()->post($url, $paramaters);
        if (empty($info)) {
            abort(555, 'empty result');
        }
        // 返回结果
        $obj = $info->json();
        Log::debug('request', [$url, $paramaters, $obj]);
        if ($obj['ok']) {
            return $obj['result'] === true ? ($obj['description'] ?? 'success') : $obj['result'];
        } else {
            abort($obj['error_code'], $obj['description'] ?? $obj['error_code']);
        }
    }

    /**
     * 方法调用
     */
    public function call(string $name, array $content = []) : mixed
    {
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
