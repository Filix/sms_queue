<?php
namespace Filix\SMS\Log;

use Filix\SMS\Message\Message;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
interface LogInterface{
    
    public function log(Message $message, $flag = '');
}
