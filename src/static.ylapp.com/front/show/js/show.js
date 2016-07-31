var opt={
    getResource:function(index,render){
		$.getJSON('/show/data/json/' + (index+1) + '?c=' + cid, function(data){
			var elems = [];
			if (data.length) {
				for (var i in data) {
					var vo = data[i];
					elems.push( ['<div class="cell"><div class="showlist"><div class="goodinfo">', '<a class="goodinfo-showPic" href="http://bbs.shikee.com/yesvalue.php?mod=showshop&uid=', vo.uid, '&showshopid=', vo.id, '" target="_blank" title="查看详细">', '<img width="', vo.img_width, '" height="', vo.img_height, '" src="', vo.img_url, '"></a>', '<h3 class="elps"><a href="http://detail.zhonghuasuan.com/', vo.gid, '.htm" target="_blank" title="查看商品：', vo.title, '">', vo.title, '</a></h3>', '<div class="goodinfo-reply-see clearfix">', '<span class="goodinfo-skShare">分享(<em>', vo.shares, '</em>)</span>', '<div class="goodinfo-view">浏览(', vo.views, ')&nbsp;&nbsp; 评论(<em>', vo.comments, '</em>)</div>', '</div></div><div class="message"><div class="shikee-info">', '<a target="_blank" class="shikee-icon" href="http://bbs.shikee.com/space-uid-', vo.uid, '.html" title="查看用户：', vo.uname, '">', '<img height="40" width="40" src="http://uc.shikee.com/avatar.php?uid=', vo.uid, '&size=small" />', '</a><p><a class="shikee-name" href="http://bbs.shikee.com/space-uid-', vo.id, '.html">', vo.uname, '</a></p>', '<p class="leaveTime">', vo.posttime, '</p>', '</div><p class="shikee-words">', vo.words, '</p>', '</div></div></div>'].join('') );
				}
		    }
		    render(elems.join(''));

		});
    },
    column_width:240,//列宽
    auto_imgHeight:false
};

$('#waterfall_container').waterfall(opt);
// $(window).triggerHandler("scroll");


//判断浏览器是否支持 box-shadow 然后应用不同的样式
(function(){
	var supportsCSS = function(prop) {

	   var style = document.createElement('div').style,
	      prefix = 'khtml o moz ms webkit'.split(' '),
	      len = prefix.length;
	  
	   supportsCSS = function(prop) { //重定义

	         //支持标准的(原生的)
	         if ( prop in style ) return true;
	         //if ('-ms-'+prop in style) return true;

	         //支持需要前缀的
	         prop = prop.replace(/^[a-z]/, function(val) { //首字母大写
	            return val.toUpperCase();
	         });  
	         while(len--) {
	            if ( prefix[len] + prop in style ) return true;
	         }

	         //不支持
	         return false;
	   };
	   
	   return supportsCSS(prop);
	};

	var bodyClass = document.body.className;
	if( !supportsCSS("boxShadow") ){
		document.body.className = bodyClass? (bodyClass + ' no-boxShadow') : "no-boxShadow";
	}
	
})();