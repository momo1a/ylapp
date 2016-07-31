Loader.use("frame").run(function () {
    $(function () {
        if (zd.shop > 0) {
            $('.zd-line').highcharts({
                chart: {
                    type: 'line',
                    backgroundColor: "#FAFAFA"
                },
                title: {
                    text: '<div class="zd-line-hd">'
                    + '<div class="f-cb">'
                    + '<p class="f-fl"><span>本期节省了<em>' + zd.save + '</em>元</span>（实际返现金额以互联支付为准）</p>'
                    + '<a class="f-fr" target="_blank" href="' + zd.detail_url + '">查看本期支出明细</a>'
                    + '</div>'
                    + '<p style="text-align:right;"><i class="zd-line-actual"></i>实际支出' + zd.actual + '元，<i class="zd-line-shop"></i>购物支出' + zd.shop + '元</p>'
                    + '</div>',
                    useHTML: true,
                    align: "left"
                },
                xAxis: {
                    categories: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"]
                },
                yAxis: {
                    min: 0,
                    title: {enabled: false},
                    labels: {enabled: false},
                    gridLineWidth: 1,
                    gridLineDashStyle: 'ShortDash'
                },
                credits: {enabled: false},
                legend: {enabled: false},
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function () {
                        return '<i class="zd-line-shop"></i>' + this.points[0].y + '元<br/>' +
                            '<i class="zd-line-actual"></i>' + this.points[1].y + '元<br/>';
                    }
                },
                series: [{
                    name: '购物支出',
                    color: '#FFA60A',
                    dataLabels: {enabled: false},
                    data: zd.shop_detail
                }, {
                    name: '实际支出',
                    color: '#51ACE3',
                    dataLabels: {enabled: false},
                    data: zd.actual_detail
                }]
            });

            //饼图
            var cat = {
                1: {name: '潮流女装', color: '#F05DCC'},
                2: {name: '精品男装', color: '#F05D6C'},
                3: {name: '鞋子箱包', color: '#DC9764'},
                4: {name: '时尚配饰', color: '#797FF6'},
                5: {name: '美食特产', color: '#F3A213'},
                6: {name: '数码家电', color: '#5DA2F0'},
                7: {name: '家居日用', color: '#52C444'},
                8: {name: '美容护肤', color: '#BE5DF0'},
                9: {name: '综合商品', color: '#A9BB31'},
                83: {name: '母婴用品', color: '#27C1CA'}
            };
            $('.zd-pie-bd').highcharts({
                chart: {
                    backgroundColor: '#FAFAFA'
                },
                title: {
                    align: 'left',
                    text: '本期所有商品类目',
                    style: {
                        fontSize: '16px',
                        fontFamily: '\\5FAE\\8F6F\\96C5\\9ED1',
                        color: '#444'
                    }
                },
                tooltip: {
                    formatter: function () {
                        return this.key + ": " + this.y + "件";
                    }
                },
                credits: {enabled: false},
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        dataLabels: {
                            style: {
                                fontSize: '12px',
                                fontFamily: '\\5FAE\\8F6F\\96C5\\9ED1',
                                color: '#666'
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: '抢购数量',
                    size: '70%',
                    innerSize: '50%',
                    data: (function () {
                        var dict = zd.statistical;
                        var i, total = 0, ret = [], html = '';
                        for (i in dict) {
                            if (dict.hasOwnProperty(i) && cat.hasOwnProperty(i)) {
                                total += Number(dict[i]);
                                ret.push({
                                    name: cat[i].name,
                                    color: cat[i].color,
                                    y: dict[i]
                                });
                            }
                        }
                        for (i = 0; i < ret.length; i++) {
                            html += '<li><i style="background-Color: ' + ret[i].color + ';"></i>' + ret[i].name + ' ' + ((ret[i].y / total * 100).toFixed(1)) + '%<\/li>';
                        }
                        $(".zd-pie-legend-inner").append('<ul>' + html + '</ul>');
                        return ret;
                    })()
                }]
            });
        }
    });

    $(function ($) {
        var percent = parseFloat(zd.percentage);
        var p = parseInt(percent / 12.5) + (percent % 12.5 > 0 ? 1 : 0);
        $(".zd-match em").html(percent + "%");

        //给其带点动画效果，匀速变化，持续1秒
        new CL_Animate("linear").run(function (i) {
            var p = parseFloat((percent * i).toFixed(1));
            $(".zd-match em").html(p + "%");
            $(".zd-match i:lt(" + (parseInt(p / 12.5) + (p % 12.5 > 0 ? 1 : 0)) + ")").not(".zd-match-people-excel").addClass("zd-match-people-excel");
        });
    });

    /*下拉框选择js*/
    $(function () {
        var J_year = $("#J_year").data("frame.form.select"),
            J_mon = $("#J_mon").data("frame.form.select");
        var ym_list = ym,
            default_y = default_ym.year, /*默认选中的年*/
            default_m = default_ym.mon, /*默认选中的月份*/
            year_arr = [], /*下拉框年*/
            mon_arr = [{value: "0", name: "请选择"}];
        /*下拉框月*/
        var i, key = [];
        for (i in ym_list) {
            (ym_list.hasOwnProperty(i) && typeof(i) == "string" && ym_list[i]) && key.push(i);
        }
        /*遍历出年份数组year_arr */
        for (var i = 0; i < key.length; i++) {
            var val = key[i];
            year_arr.push({name: val, value: val})
        }
        /*遍历出当前年的月份数组mon_arr*/
        for (var i = 0; i < ym_list[default_y].length; i++) {
            var val = ym_list[default_y][i];
            mon_arr.push({name: val, value: val});
            J_mon.append({name: val, value: val});
        }

        J_year.set_list(year_arr);
        /*动态创建年份下拉框*/
        /*选择默认年*/
        for (var i = 0; i < year_arr.length; i++) {
            if (default_y === year_arr[i].value) {
                J_year.set_index(i)
            }
        }
        /*选择默认月*/
        for (var i = 0; i < mon_arr.length; i++) {
            if (default_m === mon_arr[i].value) {
                J_mon.set_index(i)
            }
        }

        //监听年下拉框选择变化
        J_year.Event.on("set_value", function (value) {
            mon_arr = [{value: "0", name: "请选择"}];
            for (var i = 0; i < ym_list[value].length; i++) {
                var val = ym_list[value][i];
                mon_arr.push({name: val, value: val})
            }
            J_mon.set_list(mon_arr);
        });

        //监听月下拉框选择变化
        J_mon.Event.on("set_value", function (value) {
            if (value == "0") return false;
            window.location.href = shs.site('buyer') + "bill/search/?year=" + J_year.get_value() + '&month=' + value + '&rnd=' + Math.random();
        });
    });
});
