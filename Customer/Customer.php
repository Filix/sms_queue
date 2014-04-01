<?php
namespace Filix\SMS\Customer;

use Filix\SMS\Queue\QueueInterface;
use Filix\SMS\SmsService\ServiceInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
abstract class Customer {
    
    /*
     * 队列
     */
    protected $queue;

    /*
     * sms服务
     */
    protected $service;

    public function __construct(ServiceInterface $service, QueueInterface $queue)
    {
        $this->queue = $queue;
        $this->service = $service;
    }

    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        
        return $this;
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function setService(ServiceInterface $service)
    {
        $this->service = $service;
        
        return $this;
    }

    public function getService()
    {
        return $this->service;
    }
    
    /*
     * 从queue中获取信息并发送
     */
    abstract function fetchAndSend($number = 1);

}
