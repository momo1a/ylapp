/**
 * Queue: 队列执行类
 * Author: 陆楚良
 * Version: 0.0.4
 * Date: 2014/09/10
 * QQ: 519998338
 *
 * 注：本类设计思路来源于网友共享
 *
 * https://git.oschina.net/luchg/Queue.js.git
 *
 * License: http://www.apache.org/licenses/LICENSE-2.0
 */
function Queue(){
	this.__list__ = [];
	this.__canExecute__ = true;
	this.__timer__ = null;
}
Queue.prototype = {
    queue:function(arg1,arg2){
        var f = (typeof arg1=="function") ? arg1 : ((typeof arg2=="function") ? arg2 : null);
        var t = (typeof arg1=="number") ? arg1 : ((typeof arg2=="number") ? arg2 : 0);
        this.__list__[this.__list__.length] = [f,t];
        this.__canExecute__ && this.dequeue();
        return this;
    },
    dequeue:function(){
        var s = this.__list__.shift(),self=this;
        this.__canExecute__ = false;
        if(s){
            clearTimeout(this.__timer__);
            this.__timer__ = setTimeout(function(){
                (typeof s[0]=="function") && s[0].call(self);
            },s[1]);
        }else{
            this.__canExecute__ = true;
        }
        return this;
    },
    clear:function(){
        clearTimeout(this.__timer__);
        this.__list__ = [];
        this.__canExecute__ = true;
        return this;
    },
    clearOther:function(){
        this.__list__ = [];
        return this;
    }
};