<?php
namespace Filix\SMS\Customer;

use Filix\SMS\Queue\QueueInterface;
use Filix\SMS\SmsService\ServiceInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class PrimaryCustomer extends Customer {
    
    protected$secondaryQueue;
    
    public function __construct(ServiceInterface $service, QueueInterface $primaryQueue,  QueueInterface $secondaryQueue)
    {
        parent::__construct($service, $primaryQueue);
        
        $this->secondaryQueue = $secondaryQueue;
    }

    public function fetchAndSend($number = 1)
    {
        $messages = $this->queue->pop($number);
        $errors = $this->service->send($messages);
        
        /*
         * 发送失败的，放入备用queue
         */
        foreach($errors as $message){
            $this->secondaryQueue->push($message);
        }
    }

}
