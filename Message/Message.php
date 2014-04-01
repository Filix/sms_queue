<?php

namespace Filix\SMS\Message;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class Message implements MessageInterface
{

    protected $mobiles = array();
    protected $content;
    protected $createdAt;
    protected $sendAt;
    protected $result = false;

    public function __construct()
    {
        $this->createdAt = time();
    }

    public static function init(array $message)
    {
        $m = new self();
        return $m->setContent($message['content'])
                 ->setMobiles($message['mobiles']);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getMobiles()
    {
        return $this->mobiles;
    }

    public function setMobiles(array $mobiles)
    {
        $this->mobiles = $mobiles;

        return $this;
    }

    public function addMobile($mobile)
    {
        $this->mobiles[] = $mobile;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($time)
    {

        $this->createdAt = $time;

        return $this;
    }
    
    public function getSendAt()
    {
        return $this->sendAt;
    }

    public function setSendAt($time)
    {

        $this->sendAt = $time;

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {

        $this->result = $result;

        return $this;
    }

    public function __toString()
    {
        return json_encode(array(
            'mobiles' => $this->getMobiles(),
            'content' => $this->getContent()
        ));
    }

}
