<?php


namespace App\Notifications;


class notification extends \Illuminate\Notifications\Notification
{
    protected $message;

    /**
     * notification constructor.
     * @param $message
     * 证书到期提醒消息类型
     */
    public function __construct($message)
{
    $this->message = $message;
}
    public function via(){
        return ['database'];
    }

    /**
     * by you
     * @return array
     * 数据库消息通知
     */
    public function toDatabase()
    {
        return [$this->message];
    }
}
