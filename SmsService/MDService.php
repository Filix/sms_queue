<?php

namespace Filix\SMS\SmsService;

/**
 * 
 * @author Filix <suoyunpeng@pajk.cn>
 * 
 */
class MDService implements ServiceInterface {

    protected $url = 'http://sdk2.zucp.net:8060/webservice.asmx/mt';
    
    protected $sn = 'SDK-BBX-010-20020';
    
    protected $password = 'f57A403-';
    
    protected $pwd;

    public function __construct()
    {
        $this->pwd = strtoupper(md5($this->sn . $this->password));
    }

    public function enable()
    {
        return true;
        ;
    }

    /*
     * @return array 发送失败的message
     */

    public function send(array $messages)
    {
        if (empty($messages))
            return array();
        $queue = curl_multi_init();
        $map = array();

        foreach ($messages as $key => $message) {
            $ch = curl_init();
            $mobiles = implode(',', $message->getMobiles());
            $u = $this->url . "?sn={$this->sn}&pwd={$this->pwd}&mobile={$mobiles}&content={$message->getContent()}&ext=&stime=&rrid=";
            curl_setopt($ch, CURLOPT_URL, $u);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_multi_add_handle($queue, $ch);
            $map[(string) $ch] = $key;
//            $map[$key] = $ch;
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
        foreach ($responses as $key => $response) {
            if (!$response) {
                $errors[] = $messages[$key];
            } else {
                $dom = \DOMDocument::loadXML($response['results']);
                $elements = $dom->getElementsByTagName("string");
                $element = $elements->item(0);
                if (substr($element->nodeValue, 0, 1) == '-') {
                    $errors[] = $messages[$key];
                }
            }
        }
        return $errors;
    }

}
