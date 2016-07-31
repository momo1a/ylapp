$(function() {
    /*=== sidebar ===*/
    var control = {
        isOpen: 0,
        open: function() {
            control.isOpen = 1;
            $('#main').addClass('activeState');
        },
        close: function() {
            control.isOpen = 0;
            $('#main').removeClass('activeState');
        },
        toggle: function() {
            var fn = control.isOpen ? "close" : "open";
            control[fn]();
        }
    }

    $('#btn-cat').on('click', function(e) {
        control.toggle();
        e.stopPropagation();
    });

    $('#main').on('click', function() {
        control.isOpen && control.close();
    });

    /*=== sorting ===*/
    var sortingStatus = {
		 "status2":{
            "target":"#sorting-time",
            "class":"icon-sorting-time-up",
            "toggleOrderType":"3"
        },
        "status3":{
            "target":"#sorting-time",
            "class":"icon-sorting-time-down",
            "toggleOrderType":"2"
        },
		 "status5":{
            "target":"#sorting-popularity",
            "class":"icon-sorting-popularity-on",
            "toggleOrderType":"5"
        },
		 "status6":{
            "target":"#sorting-price",
            "class":"icon-sorting-price-down",
            "toggleOrderType":"7"
        },
        "status7":{
            "target":"#sorting-price",
            "class":"icon-sorting-price-up",
            "toggleOrderType":"6"
        }
    }
    function sorting () {
        var listType = getQueryString('orderType'),info, href = window.location.href;
        if (!sortingStatus['status'+listType]) return;
        info = sortingStatus['status'+listType];
        var reg = new RegExp("(^|&)orderType=([^&]*)", "i");
        var url = href.replace(reg,'&orderType='+info['toggleOrderType']);
       $(info["target"]).attr('href',url).find('i').attr('class',info['class']);
    }
    sorting();

})

