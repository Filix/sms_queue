<?php
namespace Filix\SMS\SmsService;

use Filix\SMS\Log\CsvLog;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class BWService implements ServiceInterface{

    protected $url = 'http://service2.hbsmservice.com:8080/sms_send2.do';
    
    protected $corp_id;
    
    protected $corp_pwd;
    
    protected $corp_service;
    
    protected $log;
    
    public function __construct($corp_id, $corp_pwd, $corp_service)
    {
        $this->corp_id = $corp_id;
        $this->corp_pwd = $corp_pwd;
        $this->corp_service = $corp_service;
        $this->log = new CsvLog();
    }

    /*
     * todo
     */
    public function enable()
    {
        return true;
    }
    
    /*
     * @return array 发送失败的message
     */
    public function send(array $messages)
    {
        if(empty($messages)) return array();
        $queue = curl_multi_init();
        $map = array();
        foreach ($messages as $key => $message) {
            // create cURL resources
            $ch = curl_init();
            $corp_msg_id = time() + getmypid() + $key;
            // set URL and other appropriate options
            $post = sprintf("msg_content=%s&corp_id=%s&corp_pwd=%s&corp_service=%s&mobile=%s&corp_msg_id=%s&ext=%s", 
                    $message->getContent(), $this->corp_id, $this->corp_pwd, $this->corp_service, 
                    implode(',', $message->getMobiles()), $corp_msg_id, '');
            curl_setopt($ch, CURLOPT_URL, $this->url); //设置链接
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded;charset=gbk"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置是否返回信息
            curl_setopt($ch, CURLOPT_POST, 1); //设置为POST方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); //POST数据
            curl_multi_add_handle($queue, $ch);
            $map[(string) $ch] = $key;
        }

        $active = null;

        $responses = array();
        do {
            while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);

            if ($code != CURLM_OK) {
                break;
            }

            // a request was just completed -- find out which one
            while ($done = curl_multi_info_read($queue)) {

                // get the info and content returned on the request
                $info = curl_getinfo($done['handle']);
                $error = curl_error($done['handle']);
                $results = curl_multi_getcontent($done['handle']);
                $responses[$map[(string) $done['handle']]] = compact('info', 'error', 'results');

                // remove the curl handle that just completed
                curl_multi_remove_handle($queue, $done['handle']);
                curl_close($done['handle']);
            }

            // Block for data in / output; error handling is done by curl_multi_exec
            if ($active > 0) {
                curl_multi_select($queue, 0.5);
            }
        } while ($active);

        curl_multi_close($queue);
        
        $errors = array();
        $now = time();
        foreach($responses as $key => $response){
            $m = $messages[$key];
            $m->setSendAt($now);
            if(!$response || strpos($response['results'], '0#') !== 0){
                $errors[] = $m;
            }else{
                $m->setResult(true);
            }
            $this->log->log($m, 'baiwu');
            
        }
        return $errors;
    }

}
