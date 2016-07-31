<?php
/**
 * 读取网页缓存，若有效则直接返回，否则将输出存为缓存
 * 操作中判断两个get变量
 * fc:forbid cache 禁用缓存
 * sd:show debug info 在页面最后加上调试信息
 *
 * @param filename 缓存的文件名；（存放在项目cache文件夹下）
 * @param expire_sec 缓存过期时间（单位：秒）
 *
 * @author 温守力
 * @version 13.7.9
 *
 */
function html_cache($filename, $expire_sec = 30) {
    $filename = APPPATH . 'cache/' . $filename;
    if (!isset($_GET['fc']) && file_exists($filename) && (time() - filemtime($filename) < $expire_sec)) {
        readfile($filename);
        exit ;
    }

    ob_start();
    register_shutdown_function("html_cache_save_file", $filename);
}

/**
 * 回调函数：保存输出到缓存文件
 */
function html_cache_save_file($filename) {
    $html = ob_get_clean();
    $html = html_minify($html);
    file_put_contents($filename, $html);
    echo $html;
}

/**
 * 压缩html代码函数-改写自WP-HTML-Compression
 * 备注：压缩消耗时间相对较长，建议用在不频繁更新的情况
 *
 * @author 温守力
 * @version 13.7.22
 *
 * @param $html 需要压缩的html代码
 * @param $show_debug_info 是否显示调试信息，默认为NULL，表示 isset($_GET['sd']) || ENVIRONMENT === 'development'
 */
function html_minify($html, $show_debug_info = NULL) {
    if ($show_debug_info === NULL) {
        $show_debug_info = isset($_GET['sd']) || ENVIRONMENT === 'development';
    }
    $compress_css = $compress_js = $remove_comments = TRUE;
    if ($show_debug_info) {
        $raw = strlen($html);
    }

    $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';

    if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER) === false) {
        // Invalid markup
        return $html;
    }

    $overriding = false;
    $raw_tag = false;

    // Variable reused for output
    $html = '';

    foreach ($matches as $token) {
        $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

        $content = $token[0];

        $relate = false;
        $strip = false;

        if (is_null($tag)) {
            if (!empty($token['script'])) {
                $strip = $compress_js;
                $relate = $compress_js;
            } else if (!empty($token['style'])) {
                $strip = $compress_css;
            } else if ($content === '<!--wp-html-compression no compression-->') {
                $overriding = !$overriding;

                // Don't print the comment
                continue;
            } else if ($remove_comments) {
                if (!$overriding && $raw_tag !== 'textarea') {
                    // Remove any HTML comments, except MSIE conditional comments
                    $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

                    $relate = true;
                    $strip = true;
                }
            }
        } else {
            if ($tag === 'pre' || $tag === 'textarea') {
                $raw_tag = $tag;
            } else if ($tag === '/pre' || $tag === '/textarea') {
                $raw_tag = false;
            } else if (!$raw_tag && !$overriding) {
                if ($tag !== '') {
                    if (strpos($tag, '/') === false) {
                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!action|alt|content|src)=(""|\'\'))/i', '$1', $content);
                    }

                    // Remove any space before the end of a tag (including closing tags and self-closing tags)
                    $content = preg_replace('/\s+(\/?\>)/', '$1', $content);

                    // Do not shorten canonical URL
                    if ($tag !== 'link') {
                        $relate = true;
                    } else if (preg_match('/rel=(?:\'|\")\s*canonical\s*(?:\'|\")/i', $content) === 0) {
                        $relate = true;
                    }
                } else// Content between opening and closing tags
                {
                    // Avoid multiple spaces by checking previous character in output HTML
                    if (strrpos($html, ' ') === strlen($html) - 1) {
                        // Remove white space at the content beginning
                        $content = preg_replace('/^[\s\r\n]+/', '', $content);
                    }
                }

                $strip = true;
            }
        }

        if ($strip) {
            $content = str_replace(array("\t", "\r", "\n"), ' ', $content);
            while (strpos($content, '  ') !== false) {
                $content = str_replace('  ', ' ', $content);
            }

        }

        $html .= $content;
    }

    if ($show_debug_info) {
        $compressed = strlen($html);
        $savings = ($raw - $compressed) / $raw * 100;
        $savings = round($savings, 2);
        $html .= '<!--Bytes before:' . $raw . ', after:' . $compressed . '; saved:' . $savings . '%; mktime:' . date('Y-m-d H:i:s') . '; -->';
    }
    return $html;
}
?>