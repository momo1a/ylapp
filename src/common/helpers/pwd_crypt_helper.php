<?php
/**
 * 密码加密
 */

function pwd_crypt($str){
    return md5(sha1($str));
}