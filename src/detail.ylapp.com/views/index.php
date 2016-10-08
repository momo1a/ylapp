<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
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
<div class="header">
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
</div>
<div class="container">
    <div class="header-bottom">
        <div class="type">
            <h5>Article Types</h5>
        </div>
        <span class="menu"></span>
        <div class="list-nav">
            <ul>
                <li><a href="3dprinting.html">3D Printing</a></li>|
                <li><a href="materials.html">Materials</a></li>|
                <li><a href="printing.html">Printing</a></li>|
                <li><a href="filestoprint.html">Files to Print</a></li>|
                <li><a href="404.html">Videos</a></li>|
                <li><a href="about.html">About</a></li>
            </ul>
        </div>
        <!-- script for menu -->
        <script>
            $( "span.menu" ).click(function() {
                $( ".list-nav" ).slideToggle( "slow", function() {
                    // Animation complete.
                });
            });
        </script>
        <!-- script for menu -->
        <div class="clearfix"></div>
    </div>
</div>
<div class="container">
    <div class="content">
        <div class="contact-section">
            <h3 class="c-head">contact-us</h3>
            <div class="singel_right">
                <div class="lcontact span_1_of_contact">
                    <div class="contact-form">
                        <form method="post" action="">
                            <p class="comment-form-author"><label for="author">Your Name:</label>
                                <input type="text" class="textbox" value="Enter your name here..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter your name here...';}">
                            </p>
                            <p class="comment-form-author"><label for="author">Email:</label>
                                <input type="text" class="textbox" value="Enter your email here..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}">
                            </p>
                            <p class="comment-form-author"><label for="author">Message:</label>
                                <textarea value="Enter your message here..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Message';}">Enter your message here...</textarea>
                            </p>
                            <input name="submit" type="submit" id="submit" value="Submit">
                        </form>
                    </div>
                </div>
                <div class="contact_grid span_2_of_contact_right">
                    <h3>Address</h3>
                    <div class="address">
                        <i class="pin_icon"></i>
                        <div class="contact_address">
                            Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="address">
                        <i class="phone"></i>
                        <div class="contact_address">
                            1-25-2568-897
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="address">
                        <i class="mail"></i>
                        <div class="contact_email">
                            <a href="mailto:example@gmail.com">info(at)company.com</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
<div class="footer">

</div>
</body>
</html>