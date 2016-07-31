<?php
/**
 * 获取登录或指定用户信息
 * 依赖类库：crypt/cache_memcached
 * 依赖配置项：cookie_name/cookie_crypt_key/cookie_crypt_iv
 * 依赖常量：KEY_COOKIE_CRYPT/KEY_COOKIE_CRYPT_IV
 *
 * @author 温守力
 *
 * @param uid 想要获取的用户编号，留空则为获取当前登录用户
 * @param user_cache 是否使用memcache缓存，如果设为false，同时意味着更新缓存
 *
 * @return 成功返回用户信息数组array(id,name,type,status)，找不到则返回false
 * type:0（管理员）、1（买家）、2（商家）
 * status：0（异常）、1（正常）
 * 当状态为异常时，追加两个详细说明属性
 * is_activated:0(未激活)、1（已激活） - 用户中心数据库
 * is_locked:0（正常）、1（被锁定封号）、2(未激活)、4(被屏蔽) - 用户中心数据库
 * is_shs_locked:0（未锁定）、1（已锁定） - 众划算用户数据库
 */
function get_user($uid = NULL, $use_cache = TRUE) {
    $CI = &get_instance();
    if ($uid === NULL) {
        $cookie_name = $CI->config->item('cookie_name');
        $cookie = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
        if (!$cookie)
            return FALSE;

        $cookie = explode('|', $cookie);

        $CI->load->library('crypt', array('key' => KEY_COOKIE_CRYPT, 'iv' => KEY_COOKIE_CRYPT_IV));
        if (count($cookie) >= 3 && $auth = $CI->crypt->decode($cookie[2])) {
            $auths = explode('|', $auth);
            $uid = $auths[0];
        } else {
            return FALSE;
        }
    }

    $uid = intval($uid);

    // 从缓存读取用户信息
    $key = 'user_info_' . $uid;
    $CI->load->library('Cache_memcached', NULL, 'cache');
    if ($use_cache) {
        $user = $CI->cache->get($key);
        if ($user) {
            return $user;
        }
    }

    // 数据库读取并更新到缓存, 修改为读取UC从库数据库 update_by 关小龙 2015-08-27 10:38:00
    $db = $CI->load->database('ucslave', TRUE);
    $row_uc_user = $db->select('uid,username,uTypeId,isLock,isold,email,mobile')->get_where('members', array('uid' => $uid), 1)->row_array();
    if (!$row_uc_user)
        return FALSE;

    // 修改为读取众划算从库数据库 update_by 关小龙 2015-08-27 10:38:00
    $db = $CI->load->database('slave', TRUE);
    $row_shs_user = $db->select('is_lock,reg_from,mobile_valid')->get_where('user', array('uid' => $uid), 1)->row_array();
    if (!$row_shs_user)
        return FALSE;

    // 组装用户信息
    $user = array('id' => $row_uc_user['uid'] + 0, 'name' => $row_uc_user['username'], 'type' => $row_uc_user['uTypeId'] + 0,'mobile_valid'=>$row_shs_user['mobile_valid']);
   
    // 追加异常原因

    /**
     * 联盟已废除isold,这里不再判断isold,同联盟保持一致,
     * reg_from==0说明是本地注册的账号,通过第三方登录注册的账号reg_from>0,如QQ:reg_from=1,
     * 使用第三方登录的快速注册,我们默认email为空,在这里不追加异常,只给用户一个需要完善账号的标识
     * @author 韦明磊
     * @date 2014.05.19
     */
    $is_activated = 1;
    $user['allow_perfect_account'] = FALSE;
    
    if (trim($row_uc_user['mobile']) == '' && trim($row_uc_user['email'])=='')
    {
    	if ($row_shs_user['reg_from'] == 0)
    	{
    		$is_activated = 0;
    	}
    	else
    	{
    		$user['allow_perfect_account'] = TRUE;
    	}
    }

    $is_locked = $row_uc_user['isLock'];
    $is_shs_locked = $row_shs_user['is_lock'];

    $user['status'] = $is_activated && !$is_locked && $is_shs_locked<=1?1:0;
    if($user['status']===0){
        $user['is_activated'] = $is_activated;
        $user['is_locked'] = $is_locked;
        $user['is_shs_locked'] = $is_shs_locked;
    }
    
    
    // 避免数据不更新，设置3小时缓存
    $CI->cache->save($key, $user, 60 * 60 * 3);

    return $user;
}
?>