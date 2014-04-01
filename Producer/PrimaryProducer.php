<?php

namespace Filix\SMS\Producer;

use Filix\SMS\Queue\QueueInterface;
use Filix\SMS\Message\MessageInterface;
use Filix\SMS\SmsService\ServiceInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class PrimaryProducer implements ProducerInterface {
   
    /*
     * 主queue
     */
    protected $primaryQueue;

    /*
     * 备用queue
     */
    protected $secondaryQueue;

    /*
     * sms服务
     */
    protected $service;

    public function __construct(ServiceInterface $service, QueueInterface $primaryQueue, QueueInterface $secondaryQueue)
    {
        $this->service = $service;
        $this->primaryQueue = $primaryQueue;
        $this->secondaryQueue = $secondaryQueue;
    }

    public function send(MessageInterface $message)
    {
        if (!$this->primaryQueue->isStacked() && $this->service->enable()) {
            return $this->primaryQueue->push($message);
        } else {
            return $this->secondaryQueue->push($message);
        }
    }

}
