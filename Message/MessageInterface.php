<?php
namespace Filix\SMS\Message;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
interface MessageInterface {
    
    public function __toString();
    
    public function getMobiles();
    
    public function setMobiles(array $mobiles);
    
    public function addMobile($mobile);
    
    public function getContent();
    
    public function setContent($content);
    
    
}
