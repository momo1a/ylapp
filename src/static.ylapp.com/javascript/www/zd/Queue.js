/**
 * Queue: 队列执行类
 * Author: 陆楚良
 * Email: lu_chuliang@sina.com
 * Version: 0.0.2
 * Date: Wed Jan 15 2014 11:04:08 GMT+0800 (中国标准时间)
 **/
function Queue(){}
Queue.prototype = {
	__list__ : [],
	__canExecute__ : true,
	__timer__ : null,
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
    }
};