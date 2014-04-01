#SMS QUEUE

##简介

通过队列的方式发送短信，暂时实现了redis作为队列。

以实现的第三方短信服务： [百悟](http://www.baiwutong.com/)、[漫道](http://www.zucp.net)。

发送短信方，只需要将短信丢进队列。队列分主、副两个队列。    
优先选择丢进住队列，在丢进住队列前，判断若主队列消息堆积，则丢进副队列。

两个队列对应两个消费者：主消费者和副消费者。

主消费者从主队列获取消息，尝试发送，若失败则丢进副队列，有副队列负责发送。


##使用方式

###创建消息、消息入队列

```php
use Filix\SMS\Queue\RedisQueue;    
use Filix\SMS\Message\Message;    
use Filix\SMS\Producer\PrimaryProducer;    
use Filix\SMS\SmsService\BWService;    

$redis = new Redis();    
$redis->connect('127.0.0.1', 6379);    
//主队列
$primaryQueue = new RedisQueue($redis, 'PRIMARY_CHANNEL');   
//副队列
$secondaryQueue = new RedisQueue($redis, 'SECONDARY_CHANNEL');     

//创建sms
$message = new Message();
$message->setMobiles(array('13512345678', '13412345678'));
$message->setContent('Hello!');

//将百悟作为主服务
$service = new BWService($corp_id, $corp_pwd, $corp_service); 
//主生产者
$producer = new PrimaryProducer($service, $primaryQueue, $secondaryQueue);
//其实只是丢进主队列或副队列（主队列堆积时），并没有真正的发送
$producer->send($message);
```

###发送消息

创建主消费者脚本：

```php
use Filix\SMS\Queue\RedisQueue;
use Filix\SMS\Customer\PrimaryCustomer;
use Filix\SMS\SmsService\BWService;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$primaryQueue = new RedisQueue($redis, 'PRIMARY_CHANNEL');
$secondaryQueue = new RedisQueue($redis, 'SECONDARY_CHANNEL');
$service = new BWService($corp_id, $corp_pwd, $corp_service); 
$customer = new PrimaryCustomer($service, $primaryQueue, $secondaryQueue);

while(1){
    $result = $customer->fetchAndSend(3); //一次获取3条消息，并发地向服务商接口发送
    sleep(1);
}
```

##自定义

###自定义queue

实现Filix\Queue\QueueInterface接口即可，如实现自己的Mysql队列、RabbitMQ队列等。

###自定义第三方短信服务商

实现Filix\SmsService\ServiceInterface接口即可。