	<form id="goods_category_form" action="<?php echo site_url('category/add_goods_category')?>" method="post" class="window_form">
		<div class="h">
			<span>分类名称：</span>
			<div class="pingzhen clearfix">
			<input name="name" size="30" value="<?php echo $cate['name'];?>" data-rule="required|maxlength(10)" data-msg="请填写类目名称|最多10个字符"/>
			<span style="width:auto;" id="for_name"></span>
			</div>
		</div>
		<div class="h">
			<span>普通折扣：</span>
			<div class="pingzhen clearfix">
			<input name="discount"
				size="10"
				value="<?php echo $cate['discount'];?>"
				data-rule="required|discount|range(0.1,<?php echo $maxdiscount;?>)"
				data-msg="请填写折扣|只能输入数字(最多一位小数)|请输入0.1-<?php echo $maxdiscount;?>范围的数字"/>
			<span style="width:auto;" id="for_discount"></span>
			</div>
		</div>
		<div class="h">
			<span>一站成名折扣：</span>
			<div class="pingzhen clearfix">
			<input name="discount_yzcm"
				size="10"
				value="<?php echo $cate['discount_yzcm'];?>"
				data-rule="required|discount|range(0.1,<?php echo $maxdiscount_yzcm;?>)"
				data-msg="请填写折扣|只能输入数字(最多一位小数)|请输入0.1-<?php echo $maxdiscount_yzcm;?>范围的数字"/>
			<span style="width:auto;" id="for_discount_yzcm"></span>
			</div>
		</div>
		<div class="h">
			<span>名品馆折扣：</span>
			<div class="pingzhen clearfix">
			<input name="discount_mpg"
				size="10"
				value="<?php echo $cate['discount_mpg'];?>"
				data-rule="required|discount|range(0.1,<?php echo $maxdiscount_mpg;?>)"
				data-msg="请填写折扣|只能输入数字(最多一位小数)|请输入0.1-<?php echo $maxdiscount_mpg;?>范围的数字"/>
			<span style="width:auto;" id="for_discount_mpg"></span>
			</div>
		</div>
        <div class="h">
            <span>众分期折扣：</span>
            <div class="pingzhen clearfix">
                <input name="discount_zfq"
                       size="10"
                       value="<?php echo $cate['discount_zfq'];?>"
                       data-rule="required|discount|range(0.1,<?php echo $maxdiscount_zfq;?>)"
                       data-msg="请填写折扣|只能输入数字(最多一位小数)|请输入0.1-<?php echo $maxdiscount_zfq;?>范围的数字"/>
                <span style="width:auto;" id="for_discount_mpg"></span>
            </div>
        </div>
		<div class="h">
			<span>排序：</span>
			<div class="pingzhen clearfix">
			<input name="sort" size="10" value="<?php echo $cate['sort'];?>"/>
			</div>
		</div>
		<div class="h" id="errmsg" style="font-size:14px;font-weight:bold;text-align:center;color:red;"></div>
		<input type="hidden" name="id" value="<?php echo $cate['id'];?>" />
		<input type="hidden" name="pid" value="<?php echo $cate['pid'];?>" />
		<input type="hidden" name="dosave" value="yes" />
	</form>