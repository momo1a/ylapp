/* 返回顶部 功能 */
var backToTop = function(h,t){ /*参数：滚动多高才出现该元素, 滚回顶部要多久*/

    // 不支持IE6
    var isIE6 = !-[1,] && !window.XMLHttpRequest;
        if (isIE6) { return }
    
    // 设置默认值
    var h = h || 300;
    var t = t || 300;
    var toTop = document.getElementById('js_to_top');
        if(!toTop) {
            var toTop = document.createElement('div');
            toTop.id = 'js_to_top';
            toTop.className = 'ui-to-top';
            toTop.innerHTML = '<p><a href="javascript:void(0);" title="回到顶部" style="display: none;">Top</a></p>';
            document.body.appendChild(toTop);
        }

    var toTopBtn = toTop.getElementsByTagName('a')[0];
    toTopBtn.onclick = function(){
        $('body, html').animate({scrollTop:0}, t);  // 'html' 兼容IE
        return false;
    };

    var win = $(window);
    win.scroll(function () {
        if ( win.scrollTop()>h ){
            toTopBtn.style.display = 'block';
        } else{
            toTopBtn.style.display = 'none';
        }
    });


}
