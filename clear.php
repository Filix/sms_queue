<?php
/*
 * 清空两个队列，慎用
 */
include_once(__DIR__ . '/config.php');

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
$redis->del(PRIMARY_CHANNEL);
$redis->del(SECONDARY_CHANNEL);