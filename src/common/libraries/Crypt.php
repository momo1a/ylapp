<?php
/**
 * crypt加/解密方法，包括：
 *  简化版：encrypt/decrypt
 *  加强版：encode/decode (此方法是从试客联盟延续过来的双重加/解密）
 *
 * @author 温守力
 * @version 13.6.22
 */
class Crypt{
    protected $key;
    protected $iv;
    
    /**
     * $config['key'] string 密钥
     * $config['iv] string 密钥向量数组，用逗号分割，例如：'0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF'
     */
    function __construct($config){
        $this->key = isset($config['key'])?$config['key']:die('缺少参数：key');
        $iv = isset($config['iv'])?$config['iv']:die('缺少参数：iv');
        $iv = explode(',', $iv);

        $this -> iv = '';
        foreach ($iv as $byte){
            $byte = hexdec(trim($byte));
            $this -> iv .= chr($byte);
        }
    }

    protected function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    protected function encrypt($str, $key) {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this -> pkcs5Pad($str, $size);
        return base64_encode((mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $this ->iv)));
    }

    protected function decrypt($str, $key) {
        $strBin = base64_decode($str);
        $str = mcrypt_cbc(MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $this -> iv);
        $str = $this -> pkcs5Unpad($str);
        return $str;
    }

    public function encode($str) {
        $en = $this -> encrypt($str, $this -> key);
        $key2 = substr(md5($en), 0, 8);
        $en2 = $this -> encrypt($en, $key2);
        return substr_replace($en2, $key2, 5, 0);
    }

    public function decode($str) {
        $key2 = substr($str, 5, 8);
        $en2 = substr_replace($str, '', 5, 8);
        $str = $this -> decrypt($en2, $key2);
        return $this -> decrypt($str, $this -> key);
    }
}