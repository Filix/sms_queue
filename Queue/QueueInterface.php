<?php
namespace Filix\SMS\Queue;

use Filix\SMS\Message\MessageInterface;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
interface QueueInterface {
    
    /*
     * 入队列
     * @return boolean
     */
    public function push(MessageInterface $message);
    
    /*
     * 出队列
     */
    public function pop($number = 1);
    
    /*
     * 消息是否堆积
     */
    public function isStacked();
    
}
