<?php
/*
 * 生成新消息，放入主queue中
 */
include_once(__DIR__ . '/config.php');

use Filix\SMS\Queue\RedisQueue;
use Filix\SMS\Message\Message;
use Filix\SMS\Producer\PrimaryProducer;
use Filix\SMS\SmsService\BWService;

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
$primaryQueue = new RedisQueue($redis, PRIMARY_CHANNEL);
$secondaryQueue = new RedisQueue($redis, SECONDARY_CHANNEL);

$message = new Message();
$message->setMobiles(array('13764528569'));
//$message->setMobiles(array('13764528569', '13482089506'));

$producer = new PrimaryProducer(new BWService(), $primaryQueue, $secondaryQueue);
//while (1){
    $message->setContent(date('Y-m-d/H:i:s'));
    $result = $producer->send($message);
    echo $result ? "success\n" : "fail\n";
//    sleep(1);
//}
    