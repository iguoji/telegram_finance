<?php

namespace App\Telegram;

use App\Jobs\SendMessage;
use App\Models\TelegramRobot;
use App\Models\TelegramUser;
use Illuminate\Http\UploadedFile;
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
     * 机器人模型
     */
    public TelegramRobot $robot;

    /**
     * 构造函数
     */
    public function __construct(string $token = null, string $username = null, TelegramRobot $robot = null)
    {
        // 基本参数
        if (!is_null($token)) {
            $this->token = $token;
        }
        if (!is_null($username)) {
            $this->username = $username;
        }
        if (!is_null($robot)) {
            $this->robot = $robot;
        }
    }

    /**
     * 匹配模式
     */
    public function match(string $text) : array
    {
        // 全部回调
        $callbacks = config('telegram.callbacks', []);
        // 全部参数
        $parameters = config('telegram.parameters', []);
        // 循环匹配
        foreach ($callbacks as $abstract => $class) {
            // 直接相等
            if ($abstract == $text) {
                // 返回结果
                return [$abstract, $class, null];
            }
            // 支持参数
            if (isset($parameters[$abstract])) {
                $param = $parameters[$abstract];
                // 支持前缀参数，并结尾相等，例如：参数 回调
                if (isset($param['pre']) && $param['pre'] && isset($param['suf']) && !$param['suf'] && str_ends_with($text, $abstract)) {
                    return [$abstract, $class, trim(mb_substr($text, 0, -mb_strlen($abstract)))];
                }
                // 支持后缀参数，并开头相等
                if (isset($param['pre']) && !$param['pre'] && isset($param['suf']) && $param['suf'] && str_starts_with($text, $abstract)) {
                    return [$abstract, $class, trim(mb_substr($text, mb_strlen($abstract)))];
                }
                // 前后缀都支持，并存在其中
                if (isset($param['pre']) && $param['pre'] && isset($param['suf']) && $param['suf'] && str_contains($text, $abstract)) {
                    return [$abstract, $class, $text];
                }
            }
        }

        // 没有找到
        return [null, null, null];
    }

    /**
     * 处理句柄
     */
    public function handle(array $context) : mixed
    {
        // 日志记录
        Log::debug('robot handle', $context);

        // 检查数据
        if (!empty($context['update_id'])) {
            // 消息对象
            $update = new Update($context);
            // 文本内容
            $text = $update->getText();
            // 匹配模式
            list($callback, $callbackClass, $argument) = $this->match($text);
            if ($callback && $callbackClass) {
                // 私聊
                if ($update->getChatType() == Chat::TYPE_PRIVATE) {
                    // 可用的命令列表
                    $matches = $this->robot->private;
                    // 存在使用该命令的资格
                    if (in_array($callback, $matches)) {
                        // 执行命令
                        return (new $callbackClass($this, $update))->handle($argument);
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
        $url = 'http://127.0.0.1:8081/bot' . $this->token . '/' . $method;

        // 循环参数
        $files = [];
        foreach ($paramaters as $field => $param) {
            if (is_object($param) && $param instanceof UploadedFile) {
                $files[$field] = $param;
            }
        }
        
        // 获取差集
        $paramaters = array_diff_key($paramaters, $files);
        
        // 执行请求
        $http = Http::timeout(3);
        foreach ($files as $field => $file) {
            $http = $http->attach($field, $file, $file->getPathname());
        }
        $info = $http->post($url, $paramaters);
        if (empty($info)) {
            abort(555, 'empty result');
        }

        // 返回结果
        $obj = $info->json();
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
