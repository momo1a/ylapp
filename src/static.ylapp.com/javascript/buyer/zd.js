//柱状图
$(function () {
    $('.zd-line').highcharts({
        chart: {
            type: 'line',
            backgroundColor: "#FAFAFA"
        },
        title: {
            text: '<div class="zd-line-hd">'
                +   '<div class="clearfix">'
                +       '<p class="floatL"><span>本期节省了<em>'+zd.save+'</em>元</span>（实际返现金额以互联支付为准）</p>'
                +       '<a class="floatR" target="_blank" href="'+zd.detail_url+'">查看本期支出明细</a>'
                +   '</div>'
                // +   '<p style="text-align:right;"><i class="zd-line-actual"></i>实际支出'+((zd.actual - zd.reward).toFixed(2))+'元，<i class="zd-line-shop"></i>购物支出'+zd.shop+'元，<i class="zd-line-reward"></i>搜索奖励金'+zd.reward+'元</p>'
                +   '<p style="text-align:right;"><i class="zd-line-actual"></i>实际支出'+zd.actual+'元，<i class="zd-line-shop"></i>购物支出'+zd.shop+'元</p>'
				+ '</div>',
            useHTML: true,
            align: "left"
        },
        xAxis: {
            categories: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"]
        },
        yAxis: {
            min: 0,
            title: {enabled:false},
            labels: {enabled: false},
            gridLineWidth: 1,
            gridLineDashStyle: 'ShortDash'
        },
        credits:{enabled:false},
        legend: {enabled:false},
        tooltip: {
        	shared: true,
        	useHTML: true,
            formatter: function() {
                return '<i class="zd-line-shop"></i>'+ this.points[0].y +'元<br/>'+
                       '<i class="zd-line-actual"></i>'+ this.points[1].y +'元<br/>';
            }
        },
        series: [
            {
                name: '购物支出',
                color: '#FFA60A',
                dataLabels: {enabled:false},
                data: zd.shop_detail
            }, {
                name: '实际支出',
                color: '#51ACE3',
                dataLabels: {enabled:false},
                data: zd.actual_detail
            }
            // , {
            //     name: '搜索奖励金',
            //     color: '#FF1313',
            //     dataLabels: {enabled:false},
            //     data: zd.reward_detail
            // }
        ]
    });
});





//饼图
$(function () {
	var cat = {
        1 :{name:'潮流女装',color:'#F05DCC'},
        2 :{name:'精品男装',color:'#F05D6C'},
        3 :{name:'鞋子箱包',color:'#DC9764'},
        4 :{name:'时尚配饰',color:'#797FF6'},
        5 :{name:'美食特产',color:'#F3A213'},
        6 :{name:'数码家电',color:'#5DA2F0'},
        7 :{name:'家居日用',color:'#52C444'},
        8 :{name:'美容护肤',color:'#BE5DF0'},
        9 :{name:'综合商品',color:'#A9BB31'},
        83 :{name:'母婴用品',color:'#27C1CA'}
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
            formatter: function() {
                return this.key+": "+this.y+"件";
            }
        },
        credits:{enabled:false},
        plotOptions: {
            pie: {
                allowPointSelect: true,
                dataLabels: {
                    style:{
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
            data: (function(){
            	var dict = zd.statistical;
            	var i,total=0,ret = [],html='';
            	for(i in dict){
            		if (dict.hasOwnProperty(i) && cat.hasOwnProperty(i)){
            			total += Number(dict[i]);
            			ret.push({
            				name : cat[i].name,
            				color: cat[i].color,
            				y: dict[i]
            			});
            		}
            	}
            	for(i=0; i<ret.length; i++){
            		html += '<li><i style="background-Color: '+ret[i].color+';"></i>'+ret[i].name+' '+((ret[i].y/total*100).toFixed(1))+'%<\/li>';
            	}
            	$(".zd-pie-legend-inner").append('<ul>'+html+'</ul>');
            	return ret;
            })()
        }]
    });
});






$(function($){
	var percent = parseFloat(zd.percentage);
	var p = parseInt(percent/12.5)+(percent%12.5>0?1:0);
	$(".zd-match em").html(percent+"%");

	//给其带点动画效果，匀速变化，持续1秒
    new CL_Animate("uniform").run(function(i){
        var p = parseFloat((percent*i).toFixed(1));
        $(".zd-match em").html(p+"%");
        $(".zd-match i:lt("+(parseInt(p/12.5)+(p%12.5>0?1:0))+")").not(".zd-match-people-excel").addClass("zd-match-people-excel");
    });
});
