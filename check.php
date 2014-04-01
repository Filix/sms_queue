<?php
/*
 * 定时向管理员手机发送短信
 */

include_once(__DIR__ . '/config.php');

use Filix\SMS\Message\Message;
use Filix\SMS\SmsService\BWService;

$service = new BWService();
while(1){
    $message = new Message();
    $message->setMobiles(array('13764528569'));
    $content = mb_convert_encoding('百悟SMS服务监测短信，send at: ' . date('Y-m-d H:i:s'), "gbk", "utf-8");
    $message->setContent($content);
    $errors = $service->send(array($message));
    unset($message);
    echo (count($errors) ? "fail at: " : "success at: ") . date('Y-m-d H:i:s') . "\n";
    sleep(60 * 60); //一小时发一次
}