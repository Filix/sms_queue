<?php
namespace Filix\SMS\SmsService;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
interface ServiceInterface {
    
    /*
     * 发送sms
     */
    public function send(array $messages);
    
    /*
     * 服务可用
     */
    public function enable();
    
}