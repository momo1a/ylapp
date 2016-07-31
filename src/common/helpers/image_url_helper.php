<?php
/**
 * 获取上传图片链接
 * 需要进行配置image_domains，测试配置如下：
 * $config['image_servers'] = array('http://192.168.1.47:8001/', 'http://192.168.1.47:8002/', 'http://192.168.1.47:8003/', 'http://192.168.1.47:8004/');
 *
 * @param id    图片id
 * @param path  图片相对路径
 * @param size  要显示的图片大小（字符串，例如'350x350'），默认为空表示原图
 *
 * @return string
 *
 * @author wen
 * @version 130622
 */
function image_url($id, $path, $size = '') {
    $CI = &get_instance();
    $servers = $CI -> config -> item('image_servers') or show_error('请先配置图片服务器！');
    $len = count($servers);
    $pos = ($id % $len);
    return $servers[$pos] . $path . ($size ? ('_' . $size . '.jpg') : '');
}
?>