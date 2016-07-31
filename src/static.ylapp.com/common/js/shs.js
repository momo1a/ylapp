var shs = function(shs, window, document, location, $){
    'use strict';
    var undefined = void 0;
    /**
     * 获取站点
     * @param   {String} site   站点名称，当为空的时候，返回当前页面所在的站点名称
     * @param   {String} path   url路径，例: shs.site('static', 'common/js/shs.js') >>> http://static.zhonghuasuan.com/common/js/shs.js
     * @return  {String}        返回站点url
     */
    shs.site = function(parse_host){
        var domain     = parse_host ? "."+parse_host[3] : '';
        var selfSite   = parse_host ?     parse_host[2] : '';
        return function(site, path){
            var ret = selfSite;
            if(site){
                ret = domain ? 'http://' + site + domain + '/' : "./"+site+"/";
            }
            return ret + (path || '');
        };
    }(location.host.match(/((\w+)\.)*(\w+?\.com)$/));
    /**
     * 获取系统版本
     * @author 陆楚良
     * @return {String} 返回系统版本号(需要页面配合设置才能返回正确的版本号，否则返回的是0)
     * 例：
     *     <meta name="ZHS-SYS-Version" content="1.0201403131651">
     * 或者：
     *     <script>window["ZHS-SYS-Version"] = "1.0201403131651";</script>
     */
    shs.sys_version = function(){
        return $.type(window["ZHS-SYS-Version"])=="string" ?  window["ZHS-SYS-Version"] : $("head meta[name=ZHS-SYS-Version]").attr("content") || "0";
    };
    /**
     * 响应式布局
     */
    shs.response = function(){
        var w  = 1220,
            $w = $(window),
            $b = $(document.body),
            s  = function(){$b[["remove", "add"][$w.width()<w ? 0: 1]+"Class"]("z-doc-wide")};
        return {
            /**
             * 开启响应式
             * @param  {Number} width 可选，响应宽度，缺省1220
             */
            open: function(width){
                w = width || 1220;
                $w.on("resize", s);s();
                this.isopen = true;
            },
            /**
             * 关闭响应式
             */
            close:function(wide){
                $w.off("resize", s);
                $b[["remove", "add"][wide ? 0: 1]+"Class"]("z-doc-wide");
                this.isopen = false;
            },
            isopen:false
        };
    }();
    /**
     * 获取静态文件url，自动拼加版本号防缓存
     * @param  {String} url  输入的静态文件url（相对于静态站点）
     * @return {String}      返回拼接后的url
     */
    shs.static = function(){
        var static_site = shs.site("static");
        var sys_version = shs.sys_version();
        return function(url){
            return static_site + url + (url.indexOf("?")>-1 ? "&" : "?") + "v=" + sys_version;
        }
    }();
    /**
     * 打包压缩静态文件，自动拼加版本号防缓存
     * shs.minify(["filename1.js","filename2.js"]);
     * >>> http://static.zhonghuasuan.com/min/?f=filename1.js,filename2.js&v=1.0201508060845
     * shs.minify("filename1.js","filename2.js");
     * >>> http://static.zhonghuasuan.com/min/?f=filename1.js,filename2.js&v=1.0201508060845
     * shs.minify("filename1.js",["filename2.js","filename3.js"]);
     * >>> http://static.zhonghuasuan.com/min/?f=filename1.js,filename2.js,filename3.js&v=1.0201508060845
     * @return {String} url
     */
    shs.minify = function(){
        return shs.site("static") + "min/?f=" + shs.fetch_string_for_array([].slice.call(arguments)).join(",") + "&v=" + shs.sys_version();
    };
    /**
     * 从多维数组中提取字符串组成一维数组
     * @return {Array}
     */
    shs.fetch_string_for_array = function(arr){
        var ret = [];
        for(var i=0;i<arr.length;i++){
            switch($.type(arr[i])){
                case "array":
                    ret = ret.concat(shs.fetch_string_for_array(arr[i]));
                    break;
                case "string":
                    ret.push(arr[i]);
                    break;
            }
        }
        return ret;
    };
    /**
     * 其它站点url配置
     * @type {Object}
     */
    shs.url = {
        // 新浪微博
        sina_weibo: 'http://weibo.com/ylapp',
        // 在线客服
        online_service: 'http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzgwMDA3OTYxN18xODYzNzVfODAwMDc5NjE3XzJf',
        // 邮件订阅
        subscription: 'http://list.qq.com/cgi-bin/qf_invite?id=12ae0780913afb80d9253856d368c7472788f78621bd5c77',
        // 试客联盟
        shikee: 'http://www.shikee.com/',
        // 互联支付
        hulianpay: 'http://www.hulianpay.com/',
        // 社区
        bbs: 'http://bbs.shikee.com/',
        // 用户空间
        uc: 'http://uc.shikee.com/',
        // 找回密码
        findpwd: 'http://ucenter.shikee.com/findpwd/',
        // 简单密码跳转页
        weak_password: 'http://login.shikee.com/home/weak_password'
    };
    /**
     * 获取GET值
     * @param  {String} name
     * @return {String}
     */
    shs.query = function(name){
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
        if (result == null || result.length < 1) {
            return '';
        }
        return decodeURIComponent(result[1]);
    };
    /**
     * 添加内嵌样式(即时生效)
     * @author 陆楚良
     * @param  {String} style
     * @return {shs}
     */
    shs.style = function(style){
        var css = document.getElementById('shs-inline-css');
        if (!css) {
            css = document.createElement('style');
            css.type = 'text/css';
            css.id = 'shs-inline-css';
            document.getElementsByTagName('head')[0].appendChild(css);
        }
        if (css.styleSheet) {
            css.styleSheet.cssText = css.styleSheet.cssText + str;
        } else {
            css.appendChild(document.createTextNode(str));
        }
        return shs;
    }
    /**
     * 简单的模板解析，仅支持简单解析，复杂功能还请使用模板引擎
     * @author 陆楚良
     * @param  {String} tpl     模板内容
     * @param  {Object} data    传递的模板数据
     * @return {String}         返回解析后的模板
     * 用法：
     *     var tpl = '<div>${name}</div>';
     *     var data= {
     *         name : "我是用户名"
     *     };
     *     shs.template(tpl, data);
     *     // output:   '<div>我是用户名</div>'
     * 新增支持多层嵌套的数组：
     *     var tpl = '<div>${user.name}</div>'; // 也可以这么写  ${user["name"]}，这么写也是合法的：${["user"]["name"]}、${["user"].name}
     *     var data= {
     *         user : {
     *             name : "我是用户名"
     *         }
     *     };
     *     shs.template(tpl, data);
     *     // output:   '<div>我是用户名</div>'
     */
    // shs.template = function(tpl, data){
    //     return tpl.replace(/\$\{(.+?)\}/g, function(m, k){
    //         return (k in data) ? data[k] : m;
    //     });
    // };
    shs.template = function(tpl, data){
        return tpl.replace(/\$\{(.+?)\}/g, function(m, k){
            var ret = data;
            k.replace(/\[ *\"(.+?)\" *\]|\[ *\'(.+?)\' *\]|\.? *([0-9a-zA-Z_]+)/g, function(m, k1, k2, k3){
                if(ret !== undefined){
                    ret = ret[k1||k2||k3];
                }else{
                    ret += m;
                }
            });
            return ret;
        });
    };
    /**
     * cookie操作
     * @param {String} name     必填，字段名，当参数仅有一个name时，为读取cookie
     * @param {String} value    选填，字段值，当value值为null时为删除cookie
     * @param {Object} options  选填，cookie详细设置：
     *                              {Number|Date}   expires     有效期(number类型:天，Date类型：有效期结束时刻毫秒单位)，缺省：不设置
     *                              {String}        domain      有效域，缺省：当前域
     *                              {String}        path        有效目录，缺省：当前目录
     *                              {Boolean}       secure      secure值为true时，在http模式中不会向服务回发Cookie的验证信息；在https模式中会认为是安全的，会回发数据。
     */
    shs.cookie = function(name, value, options) {
        if ( value !== undefined ) {
            options = options || {};
            if (value === null) {
                value = '';
                options = $.extend({}, options);
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && ( typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if ( typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString();
            }
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        } else {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    };
    /**
     * 会员操作类
     */
    shs.user = function(user){
        // 事件专用接口
        var EventResponse;
        /**
         * 获取会员信息
         * @param  {Boolean}        notEvent    在事件中获取用户信息会导致进入无穷循环，解决办法：shs.user.info(true)
         * @return {Null}{Object}               当会员已经登录时候，返回会员的登录信息，否则返回Null
         */
        user.info = function(notEvent){
            var info=null,split;
            var cookie = shs.cookie('zhs');
            if (cookie && cookie.split('|').length == 4) {
                split = cookie.split('|');
                info  = {
                    name : split[0],
                    type : split[1],
                    normal : split[3]
                }
            }
            notEvent || EventResponse && EventResponse(cookie, info);
            return info;
        };
        /**
         * 弹出登录弹窗
         * @return {[type]} [description]
         */
        user.login = function() {
            if(window.art){
                return art.dialog({
                    id : 'win-login',
                    lock : true,
                    fixed : true,
                    padding: 0,
                    title : '用户登录',
                    content: '<iframe src="' + shs.site('login') + 'iframe?dialog=1&jump=' + encodeURIComponent(location.href) + '" frameborder="0" style="width: 300px; height: 250px"></iframe>'
                });
            };
        };
        /**
         * 事件监听
         * @author 陆楚良
         */
        user.Event = function(Evt){
            var cookie;
            var info;
            var count = 0;
            var monitors = {};
            var timerId;
            var E = [
                // 登录
                {name : "login",  match: function(n,o){return (n && !o)}},
                // 注销
                {name : "logout", match: function(n,o){return (!n && o)}},
                // 未注销情况下更换登录
                {name : "change", match: function(n,o){return (!n || !o || n.name!=o.name || n.type!=o.type)}}
            ];
            function exec(monitor){
                setTimeout(function(){
                    monitor.call(user, info);
                }, 10);
            }
            function trigger(name){
                var m = monitors[name];
                if(m){
                    for(var i=0; i<m.length; i++){
                        exec(m[i]);
                    }
                }
            }
            function loop(c, n){
                if(c!=cookie){
                    cookie = c;
                    for(var i=0; i<E.length; i++){
                        E[i].match(n, info) && trigger(E[i].name);
                    }
                    info   = n;
                }
            }
            /**
             * 事件响应方法(获取会员信息时候会触发)
             * @type {Function}
             */
            EventResponse = loop;
            /**
             * 事件绑定
             * @author 陆楚良
             * @param  {string}   name     事件名
             * @param  {Function} callback 回调函数
             * 注意：事件中如若使用shs.user.info请传递一true参数：shs.user.info(true)，否则会进入无穷死循环，
             *       当然，不提倡在事件中使用shs.user.info方法，因为在回调函数中会接收到一参数，该参数值就是
             *       shs.user.info()的返回值
             */
            Evt.bind = function(name, callback){
                user.info();  // 同步数据，避免信息已变更但绑定事件后才触发
                name = name.toLowerCase();
                monitors[name] = monitors[name]||[];
                monitors[name].push(callback);
                count++;
                if(!timerId){
                    timerId = setInterval(user.info, 2000);
                }
            };
            /**
             * 事件解绑
             * @author 陆楚良
             * @param  {String}   name      事件名
             * @param  {Function} callback  需要解绑的函数
             */
            Evt.unbind=function(name, callback){
                name = name.toLowerCase();
                var m = monitors[name];
                if(m){
                    var n = [];
                    for(var i=0; i<m.length; i++){
                        if(m[i]!==callback){
                            n.push(m[i]);
                        }else{
                            count--;
                        }
                    }
                    monitors[name] = n;
                }
                if(count==0){
                    clearInterval(timerId);
                    timerId = undefined;
                }
            };
            return Evt;
        }({});
        return user;
    }({});

    return shs;
}({}, window, document, location, jQuery);
