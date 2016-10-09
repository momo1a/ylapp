<!DOCTYPE html>
<html>
<head>
    <title><?php echo $detail['title'];?>-移动医疗</title>
    <link href="<?php echo config_item('domain_static')?>admin/bootstrap/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom Theme files -->
    <link href="<?php echo config_item('domain_static')?>admin/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Custom Theme files -->
    <script src="<?php echo config_item('domain_static')?>admin/plugin/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Konstructs Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
</head>
<body>
<!-- header-section-starts -->
<!--<div class="header" style="background:gainsboro">
    <div class="container">
        <div class="logo">
            <a href="index.html"><h1>konstructs</h1></a>
        </div>
        <div class="pages">
            <ul>
                <li><a class="active" href="index.html">Articles</a></li>
                <li><a href="3dprinting.html">3D Printers</a></li>
                <li><a href="404.html">Tutorials</a></li>
            </ul>
        </div>
        <div class="navigation">
            <ul>
                <li><a href="contact.html">Advertise</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a class="active" href="contact.html">Contact Us</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>-->
<div class="container">
    <div class="header-bottom">
        <div class="row" style="text-align: center">
            <h4><?php echo $detail['title'];?></h4>
        </div>
        <!--<div class="clearfix"></div>-->
    </div>
</div>
<div class="container">
    <div class="content">
        <div class="contact-section">
            <div class="row">
                <div class="col-xs-6"><span><strong>作者：</strong></span><?php echo $detail['author']?></div>
                <div class="col-xs-6" style="text-align: right"><span><?php echo date('Y-m-d H:i:s',$detail['createTime']);?></span></div>
                <hr/>
            </div>

            <div>
                <?php echo $detail['content'];?>
            </div>
        </div>
    </div>
</div>
<div class="footer">

</div>
</body>
</html>