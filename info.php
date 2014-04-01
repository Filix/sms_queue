<?php
/*
 * 生成新消息，放入主queue中
 */
include_once(__DIR__ . '/config.php');

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
while(1){
    echo "Primary: " . $redis->llen(PRIMARY_CHANNEL) . ' Secondary: ' . $redis->llen(SECONDARY_CHANNEL) . "\n";
    sleep(1);
}