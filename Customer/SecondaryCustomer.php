<?php
namespace Filix\SMS\Customer;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class SecondaryCustomer extends Customer{
    
    public function fetchAndSend($number = 1)
    {
        $messages = $this->queue->pop($number);
        
        return $this->service->send($messages);
    }

}
