<?php
define('ROOT_DIR', __DIR__);

include_once(ROOT_DIR .  '/Customer/Customer.php');
include_once(ROOT_DIR . '/Customer/PrimaryCustomer.php');
include_once(ROOT_DIR . '/Customer/SecondaryCustomer.php');
include_once(ROOT_DIR . '/Message/MessageInterface.php');
include_once(ROOT_DIR . '/Message/Message.php');
include_once(ROOT_DIR . '/Queue/QueueInterface.php');
include_once(ROOT_DIR . '/Queue/RedisQueue.php');
include_once(ROOT_DIR . '/SmsService/ServiceInterface.php');
include_once(ROOT_DIR . '/SmsService/BWService.php');
include_once(ROOT_DIR . '/SmsService/MDService.php');
include_once(ROOT_DIR . '/Producer/ProducerInterface.php');
include_once(ROOT_DIR . '/Producer/PrimaryProducer.php');
include_once(ROOT_DIR . '/Producer/SecondaryProducer.php');
include_once(ROOT_DIR . '/Log/LogInterface.php');
include_once(ROOT_DIR . '/Log/CsvLog.php');

define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');
define('PRIMARY_CHANNEL', 'SMS_PRIMARY_CHANNEL');
define('SECONDARY_CHANNEL', 'SMS_SECONDARY_CHANNEL');
define('ADMIN_MOBILE', '13764528569');