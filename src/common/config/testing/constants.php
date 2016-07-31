<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// cookie密钥
define('KEY_COOKIE_CRYPT', '111111');
define('KEY_COOKIE_CRYPT_IV', '0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF');

// APP_SERVER通信密钥
define('KEY_APP_SERVER', '111111');

//系统版本配置文件
include COMPATH.'/config/version.php';

/**
 * 静态打包压缩文件路径
 */
define('PACK_JS_WWW', 'min/?b=common/js&f=jquery.js,CL_Loader.js,jquery/jquery.lazyload.min.js,jquery/jquery.tab.js,jquery/jquery.scrollBox.js,../../front/common/js/global.js,../../front/common/widget/backtotop/backtotop.widget.min.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_JS_WWW_DETAIL', 'min/?f=javascript/common/jquery/jquery.jsonp.js,javascript/www/detail.js,javascript/www/common/jquery.zclip.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_JS_WWW_DETAIL_SSXD', 'min/?f=common/js/jquery/jquery.jsonp.js,common/js/object/Time.object.js,front/detail/ssxd/js/ssxd.min.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_JS_WWW_INDEX_TOP', 'min/?f=javascript/www/top_banner.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_JS_WWW_TOP_NOTICE', 'min/?f=javascript/www/top_notice.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_JS_REFERRER', 'min/?f=javascript/common/referrer.min.js,javascript/common/track.min.js&v='.SYS_VERSION.SYS_BUILD);
define('PACK_CSS_WWW', 'min/?b=style/www/common&f=base.css,topbar.css,header.css,footer.css&v='.SYS_VERSION.SYS_BUILD);
define('PACK_CSS_WWW_LIST', 'min/?b=style/www&f=common/ui/paging.css,list.css&v='.SYS_VERSION.SYS_BUILD);
define('PACK_CSS_WWW_DETAIL', 'min/?f=style/www/common/ui/paging.css,style/www/common/ui/tab.css,style/www/detail.css,javascript/www/artDialog/skins/default.css&v='.SYS_VERSION.SYS_BUILD);
define('PACK_CSS_WWW_DETAIL_SSXD', 'min/?f=common/css/function.css,common/js/jquery/artDialog/skins/default.css,front/detail/ssxd/css/ssxd.css&v='.SYS_VERSION.SYS_BUILD);


/* app配置开始 */

/* app配置结束 */
