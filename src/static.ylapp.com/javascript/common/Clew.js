String.prototype.len = function () { return this.replace(/[^\x00-\xff]/g, "aa").length; }
var regGbk = /[^\x00-\xff]/;
function SizeClew(element, max, index) {
    var id = "clewTip" + index;
    var objClew = document.getElementById(id);
    var len = element.value.len();
    if (objClew == null) {
        var parent = element.parentNode;
        var clew_box = document.createElement("span");
        clew_box.setAttribute("id", id);
        parent.appendChild(clew_box);
        objClew = document.getElementById(id);
    }
    if (len > max) {
        var arr = element.value.split("");
        var arrLen = arr.length;
        var num = 0;
        for (var i = 0; i < arrLen; i++) {
            if (regGbk.test(arr[i])) {
                num += 2;
            } else {
                num++;
            }
            if (num > max) {
                element.value = element.value.slice(0, i);
                break;
            }
        }
        objClew.innerHTML = "<b id='inFont' style='color:#D80000;'>" + element.value.len() + "</b>/" + max;
    } else {
        objClew.innerHTML = "<b id='inFont' style='color:#139337;'>" + len + "</b>/" + max;
    }
}
function FixSizeClew(element, max, showid) {
    var objClew = document.getElementById(showid);
	var e=element.value.replace(/(^\s*)|(\s*$)/g, "");
    var len = e.length;
    if (len > max) {
        element.value = element.value.substring(0, max);
        objClew.innerHTML = "<b id='inFont' style='color:#D80000;'>" + element.value.length + "</b>/" + max;
    } else {
        objClew.innerHTML = "<b id='inFont' style='color:#139337;'>" + len + "</b>/" + max;
    }
}