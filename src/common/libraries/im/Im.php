<?php
/**
 * IM即时通信
 * User: momo1a@qq.com
 * Date: 2016/8/18
 * Time: 9:54
 */
require_once(dirname(__FILE__)."/src/autoload.php"); // 手动安装
use LeanCloud\Client;
use LeanCloud\Object;
use LeanCloud\CloudException;

class Im
{
    protected $_appId = 'wH8gqaaPTs34doln7Kv7Lq6H-gzGzoHsz';

    protected $_appKey = 'GruPQeVseXHl2GT7Xf0A2t5a';

    protected $_masterKey = 'oOiQwYWDqQYwdfoWD69EPRYr';

    public function __construct(){
        // 参数依次为 appId, appKey, masterKey
        Client::initialize($this->_appId, $this->_appKey, $this->_masterKey);
    }

    public function test(){
        return Client::get("/date");
    }
}

