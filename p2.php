<?php
/*
 * 生成新消息，放入主queue中
 */
include_once(__DIR__ . '/config.php');

use Filix\SMS\Queue\RedisQueue;
use Filix\SMS\Message\Message;
use Filix\SMS\Producer\SecondProducer;

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
$queue = new RedisQueue($redis, SECONDARY_CHANNEL);
$message = new Message();
$message->setMobiles(array('13764528569'));
$message->setContent(date('Y-m-d/H:i:s'));

$producer = new SecondProducer($queue);
$result = $producer->send($message);
echo $result ? "success\n" : "fail\n";