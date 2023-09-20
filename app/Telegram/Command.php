<?php

namespace App\Telegram;


/**
 * 命令类
 */
abstract class Command extends Callback
{
    /**
     * 名称
     */
    protected $name = 'start';

    /**
     * 描述
     */
    protected $description = 'Start command';

    /**
     * 命令
     */
    protected $usage = '/start';

    /**
     * 版本
     */
    protected $version = '1.2.0';

}