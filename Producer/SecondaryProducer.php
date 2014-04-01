<?php
namespace Filix\SMS\Producer;

use Filix\SMS\Queue\QueueInterface;
use Filix\SMS\Message\MessageInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class SecondProducer implements ProducerInterface {
   
    /*
     * queue
     */
    protected $queue;

    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    public function send(MessageInterface $message)
    {
        return $this->queue->push($message);
    }

}
