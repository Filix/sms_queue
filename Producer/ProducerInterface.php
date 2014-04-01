<?php
namespace Filix\SMS\Producer;

use Filix\SMS\Message\MessageInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
interface ProducerInterface {
    
    public function send(MessageInterface $message);
    
}
