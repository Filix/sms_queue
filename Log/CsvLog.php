<?php

namespace Filix\SMS\Log;

use Filix\SMS\Message\Message;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class CsvLog implements LogInterface
{

    protected $path;

    public function __construct()
    {
        $this->path = ROOT_DIR . '/logs/';
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function log(Message $message, $flag = '')
    {
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777);
        }
        $file = sprintf('%s%s.csv', date('Y-m'), $flag);
        //手机号，内容，创建时间，发送时间，发送结果
        $con = sprintf("%s,%s,%s,%s,%d\n", implode('|', $message->getMobiles()), str_replace(',', '，', $message->getContent()), date('Y-m-d H:i:s', $message->getCreatedAt()), date('Y-m-d H:i:s', $message->getSendAt()), (int) $message->getResult());
        file_put_contents($this->path . $file, $con, FILE_APPEND);
    }

}
