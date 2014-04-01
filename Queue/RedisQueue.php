<?php

namespace Filix\SMS\Queue;

use Filix\SMS\Message\MessageInterface;
use Filix\SMS\Message\Message;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class RedisQueue implements QueueInterface {

    protected $channel;

    protected $entity;
    
    /*
     * 最大消息数，大于等于该值则认为队列堆积
     */
    protected $max = 50;

    public function __construct(\Redis $entity, $channel)
    {
        $this->entity = $entity;
        $this->channel = $channel;
    }
    
    public function isStacked()
    {
        return $this->entity->llen($this->channel) >= $this->max;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(\Redis $entity)
    {
        $this->entity = $entity;
    }

    public function pop($number = 1)
    {
        $messages = array();
        for($i=0; $i < $number; $i++){
            if($message = $this->entity->blpop($this->channel, 3)){
                $messages[] = Message::init(json_decode($message[1], true));
            }else{
                break;
            }
        }
        return $messages;
    }

    public function push(MessageInterface $message)
    {
        return (bool) $this->entity->rpush($this->channel, $message->__toString());
    }
    

}
