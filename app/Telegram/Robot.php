<?php

namespace App\Telegram;

use App\Jobs\SendMessage;
use App\Models\TelegramGroup;
use App\Models\TelegramGroupOperator;
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
            if (str_starts_with($text, $abstract) && in_array($abstract, $parameters)) {
                return [$abstract, $class, trim(mb_substr($text, mb_strlen($abstract)))];
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

            // 私聊
            if ($update->getChatType() == Chat::TYPE_PRIVATE) {
                // 可用的命令列表
                $matches = $this->robot->private;
            } else {
                // 当前机器人在群里的身份变化消息
                if ($update->isMyChatMemberMessage()) {
                    // 查询该组曾经是否存在
                    $group = TelegramGroup::find($update->getChatId());
                    if (empty($group)) {
                        // 创建新组
                        $group = new TelegramGroup($update->getChat());
                        $group->status = $update->getMyNewChatMemberStatus();
                        $group->inviter = $update->getFromId();

                        // 欢迎语
                        $this->sendMessage([
                            'chat_id'       =>  $update->getChatId(),
                            'text'          =>  '感谢您把我添加到贵群！'. PHP_EOL . '当前本群的机器人管理员：@' . $update->getFromUsername(). PHP_EOL . '下一步：',
                        ]);
                    } else {
                        // 更新属性
                        $group->status = $update->getMyNewChatMemberStatus();
                        // 老身份为非组成员，那么新身份改变，必定是有人重新邀请我进群
                        if ($update->getMyOldChatMemberStatus() == Chat::STATUS_LEFT && $update->getMyNewChatMemberStatus() != Chat::STATUS_LEFT) {
                            // 更改邀请者
                            $group->inviter = $update->getFromId();

                            // 欢迎语
                            $this->sendMessage([
                                'chat_id'       =>  $update->getChatId(),
                                'text'          =>  '感谢您把我添加到贵群！'. PHP_EOL . '当前本群的机器人管理员：@' . $update->getFromUsername(). PHP_EOL . '下一步：',
                            ]);
                        }
                    }
                    // 保存更新
                    $group->saveOrFail();
                }
                // 新组成立，从老组迁移而来，From为系统
                else if (!empty($update->getMigrateFromChatId())) {
                    // 查询该组曾经是否存在
                    $group = TelegramGroup::find($update->getChatId());
                    if (empty($group)) {
                        // 创建新组
                        $group = new TelegramGroup($update->getChat());
                    }
                    // 查询老组的信息
                    $oldGroup = TelegramGroup::find($update->getMigrateFromChatId());
                    if (!empty($oldGroup)) {
                        // 从老组继承属性
                        $group->status = $oldGroup->status;
                        $group->inviter = $oldGroup->inviter;
                    }
                    // 保存新组信息
                    $group->old_id = $update->getMigrateFromChatId();
                    $group->saveOrFail();
                }
                // 老组弃用，迁移至新组，From为管理员
                else if (!empty($update->getMigrateToChatId())) {
                    // 查询老组
                    $group = TelegramGroup::find($update->getChatId());
                    if (!empty($group)) {
                        // 更新老组中的属性
                        $group->new_id = $update->getMigrateToChatId();
                        $group->saveOrFail();
                    }
                }
                // 其他消息
                else {
                    // 查询群组
                    $group = Cache::remember('telegram:group:' . $update->getChatId(), 3600, function() use($update){
                        return TelegramGroup::find($update->getChatId());
                    });
                }

                // 没有得到组信息
                if (empty($group)) {
                    return $this->sendMessage([
                        'chat_id'       =>  $update->getChatId(),
                        'text'          =>  '该组未授权，请将机器人删除重新邀请进群！',
                    ]);
                }

                // 获取身份
                if ($group->inviter == $update->getFromId()) {
                    // 邀请者就是管理员
                    $matches = $this->robot->group_administrator;
                } else {
                    // 操作员
                    $oper = TelegramGroupOperator::where('group_id', $update->getChatId())->where(function($query) use($update){
                        $query->where('user_id', $update->getFromId())->orWhere('username', '@' . $update->getFromUsername());
                    })->first();
                    if (empty($oper)) {
                        // 普通成员
                        $matches = $this->robot->group_default;
                    } else {
                        // 操作者
                        $matches = $this->robot->group_operator;
                    }
                }
            }

            // 文本内容
            $text = $update->getText();
            // 匹配模式
            list($callback, $callbackClass, $argument) = $this->match($text);
            if ($callback && $callbackClass) {
                // 存在使用该命令的资格
                if (in_array($callback, $matches)) {
                    // 执行命令
                    Log::debug('robot handle callback', [$text, $callback, $callbackClass, $argument]);
                    return (new $callbackClass($this, $update))->handle($argument);
                }
            }
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

        Log::debug('request', [$url, $paramaters]);

        // 循环参数
        $files = [];
        foreach ($paramaters as $field => $param) {
            if (is_object($param) && $param instanceof UploadedFile) {
                $files[$field] = $param;
            }
        }

        // Log::debug('request 1', []);

        // 获取差集
        $paramaters = array_diff_key($paramaters, $files);

        // Log::debug('request 2', []);
        // 执行请求
        $http = Http::timeout(3);
        foreach ($files as $field => $file) {
            $http = $http->attach($field, $file, $file->getPathname());
        }
        $info = $http->post($url, $paramaters);
        if (empty($info)) {
            abort(555, 'empty result');
        }

        // Log::debug('request 3', []);

        // 返回结果
        $obj = $info->json();

        // Log::debug('request 4', [$obj]);
        if ($obj['ok']) {
            return $obj['result'] === true ? ($obj['description'] ?? 'success') : $obj['result'];
        } else {
            abort($obj['error_code'], $obj['description'] ?? $obj['error_code']);
            return $obj['description'] ?? $obj['error_code'];
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
